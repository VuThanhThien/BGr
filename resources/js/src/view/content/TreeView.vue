<template>
  <li>
    <div :class="{folder : isFolder}" @click="toggle">
    <span>
      <i class="fas fa-user"></i>
      {{ newItem.full_name +' ( '+ newItem.email +' )' }}
      <span class="toggle-btn" v-if="isFolder">
          <i v-if="isOpen" class="fa fa-minus-square"></i>
          <i v-else class="fa fa-plus-square"></i>
      </span>
    </span>
    </div>
    <ul v-if="isFolder && isOpen">
      <TreeView
        class="item"
        v-for="(child, index) in newItem.children"
        :key="index"
        :item="child"
      ></TreeView>
    </ul>
  </li>
</template>

<script>
import ApiService from "@/core/services/api.service";
export default {
  name: "TreeView",
  props: {
    item: Object,
  },
  data: function () {
    return {
      isOpen: false,
      newData: null,
    };
  },
  computed: {
    isFolder: function () {
      return (
        (this.item.children && this.item.children.length) ||
        (this.item.children_count && this.item.children_count > 0)
      );
    },
    newItem: {
    get: function () {
      if(this.newData ==  null)
      {
          return this.item
      }
      else{
          return this.newData
      }
    },
    set: function (newValue) {
      this.newData = newValue;
    }
  }
  },
  methods: {
    toggle: function () {
      if (this.isFolder) {
        this.loadDownlines();
      }
    },
    loadDownlines() {
      let resource = "affiliates/" + this.item.id + "/downlines";
      ApiService.query(resource)
        .then(({ data }) => {
            this.newItem = data.data;
            this.isOpen = !this.isOpen;
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, { position: "bottom" });
        });
    },
  },
};
</script>

<style scoped>
.toggle-btn{
    border:  none !important;
    padding: 0 !important;
}
.bold {
  font-weight: bold;
}
.folder{
    cursor: pointer;
}
.tree {
    min-height:20px;
    padding:19px;
    margin-bottom:20px;
    background-color:#fbfbfb;
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    border-radius:4px;
    -webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    -moz-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05)
}
.tree li {
    list-style-type:none;
    margin:0;
    padding:10px 5px 0 5px;
    position:relative
}
.tree li::before, .tree li::after {
    content:'';
    left:-20px;
    position:absolute;
    right:auto
}
.tree li::before {
    border-left:1px solid #999;
    bottom:50px;
    height:100%;
    top:0;
    width:1px
}
.tree li::after {
    border-top:1px solid #999;
    height:20px;
    top:30px;
    width:25px
}
.tree li span {
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border:1px solid #999;
    border-radius:5px;
    display:inline-block;
    padding:3px 8px;
    text-decoration:none
}
.tree>ul>li::before, .tree>ul>li::after {
    border:0
}
.tree li:last-child::before {
    height:30px
}
.tree li.parent_li>span:hover, .tree li.parent_li>span:hover+ul li span {
    background:#eee;
    border:1px solid #94a0b4;
    color:#000
}
</style>
