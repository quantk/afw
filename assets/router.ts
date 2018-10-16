import VueRouter from 'vue-router';
import IndexPage from './Page/Index';
import NotesPage from './Page/Notes';

export const routes = [
    {
        path: '/',
        component: IndexPage,
        title: 'Главная'
    },
    {
        path: '/notes',
        component: NotesPage,
        title: 'Заметки'
    },
];

const router = new VueRouter({
    routes
});

export default router;