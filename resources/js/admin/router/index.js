import Vue from 'vue';
import VueRouter from 'vue-router';

import { ROLE_DEVELOPER, ROLE_ADMIN, ROLE_EDITOR, ROLE_CONTRIBUTOR } from '../../modules/util';

// page components
import Dashboard from '../pages/dashboard/App';

import PostTypes from '../pages/post-types/App';
import PostTypeEdit from '../pages/post-types/edit/App';

import CategoryTypes from '../pages/category-types/App';
import CategoryTypeEdit from '../pages/category-types/edit/App';

import Categories from '../pages/posts/categories/App';
import CategoriesEdit from '../pages/posts/categories/edit/App';

import Users from '../pages/users/App';
import UserEdit from '../pages/users/edit/App';

import Posts from '../pages/posts/App';
import PostIndex from '../pages/posts/index/App';
import PostEdit from '../pages/posts/edit/App';

import Login from '../pages/login/App';
import APITest from '../pages/test/API';
import NotFound from '../pages/404/App';

import store from '../store/index';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'dashboard',
    component: Dashboard,
  },

  {
    path: '/post-types',
    name: 'post-types',
    component: PostTypes,
    beforeEnter(to, from, next) {
      store.commit('error/setForbidden', !store.getters['auth/userRoleControll'](ROLE_DEVELOPER));
      next();
    },
  },
  {
    path: '/post-types/create',
    name: 'post-type-create',
    component: PostTypeEdit,
    beforeEnter(to, from, next) {
      store.commit('error/setForbidden', !store.getters['auth/userRoleControll'](ROLE_DEVELOPER));
      next();
    },
  },
  {
    path: '/post-types/:id',
    name: 'post-type-edit',
    component: PostTypeEdit,
    beforeEnter(to, from, next) {
      store.commit('error/setForbidden', !store.getters['auth/userRoleControll'](ROLE_DEVELOPER));
      next();
    },
  },

  {
    path: '/category-types',
    name: 'category-types',
    component: CategoryTypes,
    beforeEnter(to, from, next) {
      store.commit('error/setForbidden', !store.getters['auth/userRoleControll'](ROLE_DEVELOPER));
      next();
    },
  },
  {
    path: '/category-types/create',
    name: 'category-type-create',
    component: CategoryTypeEdit,
    beforeEnter(to, from, next) {
      store.commit('error/setForbidden', !store.getters['auth/userRoleControll'](ROLE_DEVELOPER));
      next();
    },
  },
  {
    path: '/category-types/:id',
    name: 'category-type-edit',
    component: CategoryTypeEdit,
    beforeEnter(to, from, next) {
      store.commit('error/setForbidden', !store.getters['auth/userRoleControll'](ROLE_DEVELOPER));
      next();
    },
  },

  {
    path: '/users',
    name: 'users',
    component: Users,
  },
  {
    path: '/users/create',
    name: 'user-create',
    component: UserEdit,
    beforeEnter(to, from, next) {
      store.commit('error/setForbidden', !store.getters['auth/userRoleControll'](ROLE_ADMIN));
      next();
    },
  },
  {
    path: '/users/:id',
    name: 'user-edit',
    component: UserEdit,
  },

  {
    path: '/posts',
    name: 'posts',
    component: Posts,
    children: [
      {
        path: ':slug',
        name: 'index',
        component: PostIndex,
        beforeEnter(to, from, next) {
          store.commit(
            'error/setForbidden',
            !store.getters['auth/userRoleControll'](ROLE_EDITOR) &&
              !store.getters['auth/isPermission'](to.params.slug)
          );
          next();
        },
      },
      {
        path: ':slug/create',
        name: 'create',
        component: PostEdit,
        beforeEnter(to, from, next) {
          store.commit(
            'error/setForbidden',
            !store.getters['auth/userRoleControll'](ROLE_EDITOR) &&
              !store.getters['auth/isPermission'](to.params.slug)
          );
          next();
        },
      },
      {
        path: ':slug/edit/:id',
        name: 'edit',
        component: PostEdit,
        beforeEnter(to, from, next) {
          store.commit(
            'error/setForbidden',
            !store.getters['auth/userRoleControll'](ROLE_EDITOR) &&
              !store.getters['auth/isPermission'](to.params.slug)
          );
          next();
        },
      },

      {
        path: ':slug/categories/:categorySlug',
        name: 'categories',
        component: Categories,
        beforeEnter(to, from, next) {
          store.commit(
            'error/setForbidden',
            !store.getters['auth/userRoleControll'](ROLE_EDITOR) &&
              !store.getters['auth/isPermission'](to.params.slug)
          );
          next();
        },
      },
      {
        path: ':slug/categories/:categorySlug/create',
        name: 'category-create',
        component: CategoriesEdit,
        beforeEnter(to, from, next) {
          store.commit(
            'error/setForbidden',
            !store.getters['auth/userRoleControll'](ROLE_EDITOR) &&
              !store.getters['auth/isPermission'](to.params.slug)
          );
          next();
        },
      },
      {
        path: ':slug/categories/:categorySlug/edit/:id',
        name: 'category-edit',
        component: CategoriesEdit,
        beforeEnter(to, from, next) {
          store.commit(
            'error/setForbidden',
            !store.getters['auth/userRoleControll'](ROLE_EDITOR) &&
              !store.getters['auth/isPermission'](to.params.slug)
          );
          next();
        },
      },
    ],
  },

  {
    path: '/login',
    name: 'login',
    component: Login,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next('/');
      } else {
        next();
      }
    },
  },

  {
    path: '/test/api',
    name: 'api-test',
    component: APITest,
    beforeEnter(to, from, next) {
      store.commit('error/setForbidden', !store.getters['auth/userRoleControll'](ROLE_DEVELOPER));
      next();
    },
  },

  {
    path: '*',
    name: '404',
    component: NotFound,
  },
];

const router = new VueRouter({
  mode: 'history',
  base: 'admin/',
  routes,
});

router.beforeEach((to, from, next) => {
  store.commit('error/setValidate', null);
  store.commit('error/setForbidden', false);

  if (to.name !== 'login' && !store.getters['auth/check']) {
    store.commit('auth/setReferer', to);
    next({ name: 'login' });
  } else {
    // store.dispatch('page/postTypes');
    next();
  }
});

router.afterEach((to, from) => {
  if (!store.getters['system/update']) {
    store.dispatch('system/version');
  }
});

export default router;
