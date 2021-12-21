<template>
<div>
    <CardTodoList :listObj="this.listObj">
        <template v-slot:header>
            Program basic setup
        </template>
    </CardTodoList>
</div>
</template>

<script>
import { contenTodo } from "./partials/program-basic-setup";
import CardTodoList from "./partials/CardTodoList.vue";
export default {
    components:{CardTodoList},
    data(){
        return{
            listObj: contenTodo,
        }
    }
}
</script>

<style>

</style>
