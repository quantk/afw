<template>
    <el-container>
        <el-header class="nav-header">
            <el-menu
                    class="el-menu-demo"
                    :router="true"
                    mode="horizontal"
                    background-color="#1e1e1e"
                    :default-active="activePath"
                    text-color="#fff"
                    active-text-color="#ffd04b">
                <template  v-for="(route, index) in routes"
                >
                    <div class="menu-item-div" v-if="route.children && route.children.length > 0">
                        <el-submenu :index="''+index">
                            <template slot="title">
                                <i :class="route.icon"></i>
                                <span>{{route.title}}</span>
                            </template>
                            <el-menu-item-group >
                                <el-menu-item
                                        v-for="(subRoute, subIndex) in route.children"
                                        :key="subIndex.toString()"
                                        :index="subRoute.path"
                                        :route="subRoute"
                                >
                                    <i :class="subRoute.icon"></i>
                                    <span>{{subRoute.title}}</span>
                                </el-menu-item>
                            </el-menu-item-group>
                        </el-submenu>
                    </div>
                    <div class="menu-item-div" v-else>
                        <el-menu-item :route="route" :index="route.path" :key="index.toString()">
                            <i :class="route.icon"></i>
                            <span>{{route.title}}</span>
                        </el-menu-item>
                    </div>
                </template>

            </el-menu>
        </el-header>

        <el-main>
            <router-view/>
        </el-main>

    </el-container>

</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator';
    import {routes} from './router';

    @Component
    export default class Root extends Vue {
        name: string = 'Root';
        msg: string = 'Hello, world';
        activeMenuItem: string = '1';

        get activePath () {
            return this.$route.fullPath;
        }

        routes: Array<object> = routes;

        handleSelect() {

        }
    }
</script>

<style scoped>
    .nav-header {
        background-color: #1e1e1e;
    }
    .menu-item-div {
        display: inline-block;
    }
</style>