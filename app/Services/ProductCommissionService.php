<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\ProductCommission;
use App\Models\CollectionCommission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Products\Product;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DB;
use Illuminate\Support\Arr;

Class ProductCommissionService
{
    public function getProducts($data)
    {
        $products = ProductCommission::where('group_id', $data['group_id'])->with('collection')->orderBy($data['sort_field'], $data['sort_direction'])->paginate(10);
        return $products;
    }

    public function update($id ,$data)
    {
        $product = ProductCommission::findOrFail($id);
        $product->commission_type = $data['commission_type'];
        $product->commission_amount = $data['commission_amount'];
        $product->save();
        return $product;
    }

    public function destroy($id)
    {
        $product = ProductCommission::find($id);
        if($product) {
            $product->delete();
        }
        return true;
    }

    public function bulkDelete($ids)
    {
        $shopId = Auth::user()->shop_id;
        ProductCommission::where('shop_id', $shopId)->whereIn('id', $ids)->delete();
        return true;
    }

    public function searchShopifyProduct($query)
    {
        $shopId = Auth::user()->shop_id;
        $shop = Shop::findOrFail($shopId);
        $shopName = shopNameFromDomain($shop->shop);
        $accessToken = $shop->access_token;
        $client = new \DoubleC\LaravelShopify\Shopify\Resource\GraphqlApi\GraphqlClient($shopName, $accessToken);

        $data = '{
            products(first: 10, query: "title:*'.$query.'*") {
                edges {
                  node {
                    id
                    title
                    small_image: images(first:1, maxWidth:150) {
                        edges {
                          node {
                            src
                          }
                        }
                      }
                    variants(first: 30) {
                      edges{
                        node{
                          id
                          title
                          image {
                                id
                                originalSrc
                            }
                        }
                      }

                    }
                  }
                }
              }
            }';
        $productSearchByTitle = $client->post($data);
        $products = [];
        foreach ($productSearchByTitle->data->products->edges as $p) {
            $id = getIdFromGid($p->node->id);
            $title = $p->node->title;
            $image = isset($p->node->small_image->edges[0]) ? $p->node->small_image->edges[0]->node->src : null;
            $variants = [];
            foreach ($p->node->variants->edges as $v) {
                $variants[] = [
                    'id'=>getIdFromGid($v->node->id),
                    'title'=>$v->node->title,
                    'product_id' => $id,
                    'image' => isset($v->node->image->originalSrc) ? $v->node->image->originalSrc : ""
                ];
            }
            $products[] = [
                'id' => $id,
                'title' =>$title,
                'image' => $image,
                'variants' => $variants
            ];
        }



    	// $productApi = app(Product::class);
    	// $products = $productApi->all(['title'=>$query, 'fields'=>'id,title,variants']);
    	return $products;
    }

    public function searchShopifyCollection($query)
    {
        $shopId = Auth::user()->shop_id;
        $shop = Shop::findOrFail($shopId);
        $shopName = shopNameFromDomain($shop->shop);
        $accessToken = $shop->access_token;
        $client = new \DoubleC\LaravelShopify\Shopify\Resource\GraphqlApi\GraphqlClient($shopName, $accessToken);

        $data = '{
              collections(first: 10, query: "title:*'.$query.'*") {
                edges {
                  node {
                    id
                    title
                    handle

                  }
                }
              }
            }';

        $collectionSearchByTitle = $client->post($data);
        $collections = [];
        foreach ($collectionSearchByTitle->data->collections->edges as $l) {
            $id = getIdFromGid($l->node->id);
            $title = $l->node->title;
            $handle = $l->node->handle;
            $collections[] = [
                'id' => $id,
                'title' => $title,
                'handle' => $handle
            ];

        }
        // $customCollectionApi = app(CustomCollection::class);
        // $smartCollectionApi = app(SmartCollection::class);
        // $customCollections = $customCollectionApi->all(['title'=>$query,'fields'=>'id,title','limit'=>5]);
        // $smartCollections = $smartCollectionApi->all(['title'=>$query,'fields'=>'id,title,rules', 'limit'=>5]);
        // $collections = array_merge($customCollections, $smartCollections);
        return $collections;
    }

    public function store($inputs)
    {
        $shopId = Auth::user()->shop_id;
        Validator::make($inputs, [
            'commission_amount' => 'required',
        ])->validate();

        $group = Group::findOrFail($inputs['group_id']);


        if( $inputs['data_type'] == 'product' ){
            $this->handleProduct($inputs, $shopId);
        }else{
            $res = $this->handleCollection($inputs, $shopId, $group->id);
        }

        return true;

    }

    private function handleProduct($inputs, $shopId)
    {
        $dataInsert = [];

        foreach ($inputs['data']['variants'] as $v) {
            $dataInsert[] = [
                'variant_id' => $v['id'],
                'variant_title' => $v['title'],
                'image_url' => $v['image'],
                'product_id' => $inputs['data']['product']['id'],
                'product_title' => $inputs['data']['product']['title'],
                'commission_type' => $inputs['commission_type'],
                'commission_amount' => $inputs['commission_amount'],
                'group_id' => $inputs['group_id'],
                'shop_id' => $shopId
            ];

        }
        return ProductCommission::replace($dataInsert);


    }

    private function handleCollection($inputs, $shopId, $groupId)
    {
        $shopId = Auth::user()->shop_id;
        $shop = Shop::findOrFail($shopId);
        $shopName = shopNameFromDomain($shop->shop);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $productApi = new Product($clientApi);

        DB::beginTransaction();

        $sinceId = 0;
        $collectionCommission =CollectionCommission::where('collection_id', $inputs['data']['collection']['id'])->first();
        if(!$collectionCommission){
            $collectionCommission = new CollectionCommission();
            $collectionCommission->collection_id = $inputs['data']['collection']['id'];
            $collectionCommission->title  = $inputs['data']['collection']['title'];
            $collectionCommission->handle  = $inputs['data']['collection']['handle'];
            $collectionCommission->group_id  = $groupId;
            $collectionCommission->shop_id  = $shopId;
            $collectionCommission->save();
        }

        while(1) {
            $res = $productApi->all(['collection_id'=>$collectionCommission->collection_id, 'fields'=>'id,title,variants,images','limit'=>250, 'since_id'=>$sinceId ]);
            $products = $res['products'];
            if(empty($products)){
                break;
            }else{

                $dataInsert = [];
                foreach ($products as $p) {
                    foreach ($p['variants'] as $v) {
                        $dataInsert[] = [
                            'variant_id' => $v['id'],
                            'variant_title' => $v['title'],
                            'product_id' => $v['product_id'],
                            'product_title' => $p['title'],
                            'commission_type' => $inputs['commission_type'],
                            'commission_amount' => $inputs['commission_amount'],
                            'group_id' => $inputs['group_id'],
                            'shop_id' => $shopId,
                            'collection_id' => $collectionCommission->id,
                            'image_url' => $this->getImageUrl($v['image_id'], $p['images'])
                        ];
                    }
                }
                $sinceId = Arr::last($products)['id'];
                ProductCommission::replace($dataInsert);

            }
        }


        DB::commit();
        return 1;
    }

    private function getImageUrl($imageId, $images)
    {
        foreach($images as $i){
            if($imageId == $i['id']) {
                return $i['src'];
            }
        }
        return null;
    }



}
