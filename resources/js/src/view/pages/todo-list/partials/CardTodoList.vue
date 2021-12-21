<template>
<div class="card card-custom card-stretch gutter-b ">
    <div class="card-header border-0 pt-5">
    <div class="card-title">
        <h3 class="card-title font-weight-bolder text-bixgrow">
            <slot name="header"></slot>
        </h3>
    </div>
    </div>
    <div class="card-body d-flex">
            <div class="col-xl-4">
            <ol class="title-todo-text-ul" id="title-todo-text-ul" ref="titl">
                <li v-for="(item, i) in listObj" :key="i" :i="i"
                class="title-todo-text font-weight-bolder"
                :class="{'isActive' : active == i}"
                @click="choseLine(item, i)">{{item.title}}</li>
            </ol>
        </div>
        <div class="col-xl-8">
            <div class="font-weight-bolder mb-5">{{this.title}}</div>
            <span ref="textContent" v-html="replaceTextContent(this.textContent)"></span>
        </div>
    </div>
</div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    props:["listObj"],
    data(){
        return{
            textContent: '',
            title:'',
            active: 0
        }
    },
    methods:{
        choseLine(item, i){
            if(i != 1){
                this.$refs['titl'].firstChild.classList.remove("isActive");
                this.textContent = item.content;
                this.title = item.title;
                this.active = i;
            }
        },
        replaceTextContent(text){
            if(text){
                let id = this.defaultGroup.id;
                let regisLink = "https://" + this.subDomain + "." + process.env.MIX_BASE_DOMAIN + "/#/register";
                text = text.replace(/{default_group_id}/g, id);
                text = text.replace(/{default_register}/g, regisLink);
            }else{
                text = '';
            }
        return text;
        },
    },
    computed:{
        ...mapGetters(["defaultGroup","subDomain"]),
    },
    created(){
        this.textContent = this.listObj[Object.keys(this.listObj)[0]].content;
        this.title = this.listObj[Object.keys(this.listObj)[0]].title;
    },
    mounted(){
        this.$refs['titl'].firstChild.classList.add("isActive");
    }
}
</script>

<style scoped>
.title-todo-text{
    cursor: pointer;
}
.title-todo-text:hover{
    color: #f8ab36;
    transition: .3s;
}
.isActive{
    color: #ff9900;
}
.title-todo-text-ul{
    line-height: 2rem;
}
</style>
