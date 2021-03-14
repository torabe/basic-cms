<template>
  <v-navigation-drawer app clipped v-model="$store.state.page.drawer">
    <v-list nav dense>
      <NavItem :item="dashboard" />
      <NavItem :item="postType" />
      <NavItem :item="categoryTypes" />
      <v-divider />
      <NavItem v-for="item in posts" :key="item.id" :item="item" />
      <v-divider />
      <NavItem :item="user" />
      <v-divider />
      <v-list-item @click="logout" color="primary">
        <v-list-item-icon>
          <v-icon v-text="'mdi-logout'" />
        </v-list-item-icon>
        <v-list-item-title>ログアウト</v-list-item-title>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import { ROLE_DEVELOPER, ROLE_ADMIN, ROLE_EDITOR, ROLE_CONTRIBUTOR } from '../../../modules/util';

import NavItem from './NavItem';

export default {
  components: {
    NavItem,
  },
  data() {
    return {
      dashboard: {
        title: 'ダッシュボード',
        icon: 'mdi-view-dashboard',
        route: { name: 'dashboard' },
        exact: true,
        role: true,
      },
      postType: {
        title: '投稿タイプ',
        icon: 'mdi-post-outline',
        exact: false,
        role: this.$store.getters['auth/userRoleControll'](ROLE_DEVELOPER),
        children: [
          {
            title: '一覧',
            route: { name: 'post-types' },
            exact: true,
            role: true,
          },
          {
            title: '新規登録',
            route: { name: 'post-type-create' },
            exact: true,
            role: true,
          },
        ],
      },
      categoryTypes: {
        title: 'カテゴリタイプ',
        icon: 'mdi-file-tree',
        exact: false,
        role: this.$store.getters['auth/userRoleControll'](ROLE_DEVELOPER),
        children: [
          {
            title: '一覧',
            route: { name: 'category-types' },
            exact: true,
            role: true,
          },
          {
            title: '新規登録',
            route: { name: 'category-type-create' },
            exact: true,
            role: true,
          },
        ],
      },
      user: {
        title: 'ユーザー',
        icon: 'mdi-account-details',
        exact: false,
        role: true,
        children: [
          {
            title: '一覧',
            route: { name: 'users' },
            exact: true,
            role: true,
          },
          {
            title: '新規登録',
            route: { name: 'user-create' },
            exact: true,
            role: this.$store.getters['auth/userRoleControll'](ROLE_ADMIN),
          },
          {
            title: '自ユーザー編集',
            route: { name: 'user-edit', params: { id: this.$store.getters['auth/userId'] } },
            exact: true,
            role: true,
          },
        ],
      },
    };
  },
  computed: {
    posts() {
      return this.$store.getters['page/postTypes'].map((postType) => {
        const role =
          this.$store.getters['auth/userRoleControll'](ROLE_ADMIN) ||
          this.$store.getters['auth/isPermission'](postType.slug);

        const item = {
          title: postType.name,
          icon: postType.admin_icon,
          exact: true,
          role: role,
        };

        item.children = [
          {
            title: '一覧',
            route: { name: 'index', params: { slug: postType.slug } },
            exact: true,
            role: role,
          },
          {
            title: '新規登録',
            route: { name: 'create', params: { slug: postType.slug } },
            exact: true,
            role: role,
          },
          ...postType.category_types.map((categoryType) => ({
            title: categoryType.name,
            route: { name: 'categories', params: { slug: postType.slug, categorySlug: categoryType.slug } },
            exact: true,
            role: role,
          })),
        ];

        return item;
      });
    },
  },
  methods: {
    async logout() {
      await this.$store.dispatch('page/loading', async () => {
        await this.$store.dispatch('auth/logout');

        this.$router.push({
          name: 'login',
        });
      });
    },
  },
};
</script>
