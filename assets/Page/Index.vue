<template>
    <div>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{ path: '/' }">Главная</el-breadcrumb-item>
        </el-breadcrumb>
    </div>
</template>

<script lang="ts">
    import {Vue, Component} from 'vue-property-decorator';

    @Component
    export default class IndexPage extends Vue {
        msg: string = 'Index page'
    }
</script>

<style scoped>

</style>