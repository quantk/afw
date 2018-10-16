<template>
    <div>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{ path: '/' }">Главная</el-breadcrumb-item>
            <el-breadcrumb-item :to="{ path: '/notes' }">Заметки</el-breadcrumb-item>
        </el-breadcrumb>
        <h1>Notes page</h1>
    </div>
</template>

<script lang="ts">
    import {Vue, Component} from 'vue-property-decorator';

    @Component
    export default class NotesPage extends Vue {
        name: "Notes"
    }
</script>

<style scoped>

</style>