import Vue from 'vue';
import App from './App.vue';
import Home from './components/Home.vue';
import Signup from './components/Signup.vue';
import Login from './components/Login.vue';
import UserProfile from './components/UserProfile.vue';
import LikedCourses from './components/LikedCourses.vue';
import BuyCourses from './components/BuyCourses.vue';
import EnrolledCourses from './components/EnrolledCourses.vue';
import BoughtCourses from './components/BoughtCourses.vue';
import AdminLogin from './components/AdminLogin.vue';
import Admin from './components/Admin.vue';
import VueRouter from 'vue-router';

Vue.config.productionTip = false;
Vue.use(VueRouter);

const routes = [
    { path: '/', component: Home, name: 'home' },
    { path: '/signup', component: Signup, name: 'signup' },
    { path: '/login', component: Login, name: 'login' },
    { path: '/profile/:username', component: UserProfile, name: 'Profile', props: true },
    { path: '/liked-courses/:username', component: LikedCourses, name: 'liked-courses', props: true },
    { path: '/buy-courses/:username', component: BuyCourses, name: 'buy-courses', props: true },
    { path: '/enrolled-courses/:username', component: EnrolledCourses, name: 'enrolled-courses', props: true },
    { path: '/bought-courses/:username', component: BoughtCourses, name: 'bought-courses', props: true },
    { path: '/admin-login', component: AdminLogin, name: 'admin-login' },
    { path: '/admin', component: Admin, name: 'admin' }
];

const router = new VueRouter({
    routes
});

new Vue({
    render: h => h(App),
    router
}).$mount('#app');
