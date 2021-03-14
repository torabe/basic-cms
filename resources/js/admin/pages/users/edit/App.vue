<template>
  <Content :title="getTitle" :icon="'mdi-account-details'">
    <v-form ref="user-edit" @submit.prevent="storeOrUpdate">
      <v-row>
        <v-spacer></v-spacer>
        <v-col cols="auto">
          <SubButton :to="{ name: 'users' }">一覧に戻る</SubButton>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <v-card>
            <v-card-title>
              <v-icon class="mr-1" v-text="'mdi-information-outline'" color="primary" />
              基本情報
            </v-card-title>
            <v-card-text>
              <ToggleSwitch
                :label="form.is_enable ? '利用' : '停止'"
                :error-messages="$store.getters['error/validate']('is_enable')"
                :readonly="isNotRoleAdmin"
                v-model="form.is_enable"
                v-if="!isMe"
              />
              <TextField
                label="ユーザー名"
                :error-messages="$store.getters['error/validate']('name')"
                v-model="form.name"
              />
              <TextField
                label="ログイン ID"
                :error-messages="$store.getters['error/validate']('login_id')"
                :readonly="isNotRoleAdmin"
                v-model="form.login_id"
              />
              <Select
                label="権限"
                :items="select.roles"
                item-text="name"
                item-value="id"
                :error-messages="$store.getters['error/validate']('role_id')"
                :readonly="isNotRoleAdmin"
                v-model="form.role_id"
              />
              <TextField
                label="メールアドレス"
                :error-messages="$store.getters['error/validate']('email')"
                v-model="form.email"
              />
              <TextField
                label="パスワード"
                type="password"
                hint="半角アルファベット、数字、8文字以上"
                :error-messages="$store.getters['error/validate']('password')"
                v-model="form.password"
              />
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <v-row v-if="form.permissions">
        <v-col>
          <v-card>
            <v-card-title>
              <v-icon class="mr-1" v-text="'mdi-monitor-dashboard'" color="primary" />
              編集許可
            </v-card-title>
            <v-card-text>
              <v-list>
                <template v-for="postType in $store.getters['page/postTypes']">
                  <v-list-item :key="postType.id">
                    <v-list-item-content>
                      <v-list-item-title>{{ postType.name }}</v-list-item-title>
                    </v-list-item-content>
                    <ToggleSwitch
                      :label="getPostType(postType.id).permission ? '許可' : '禁止'"
                      :error-messages="$store.getters['error/validate']('permissions')"
                      v-model="getPostType(postType.id).permission"
                    />
                  </v-list-item>
                  <v-divider :key="postType.id" />
                </template>
              </v-list>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <MainButton type="submit">{{ getButtonValue }}</MainButton>
        </v-col>
      </v-row>
    </v-form>
  </Content>
</template>

<script>
import axios from 'axios';
import { API_URL } from '../../../../config/const';
import {
  OK,
  CREATED,
  UNPROCESSABLE_ENTITY,
  ERROR_MESSAGES,
  ROLE_DEVELOPER,
  ROLE_ADMIN,
  ROLE_EDITOR,
  ROLE_CONTRIBUTOR,
} from '../../../../modules/util';

import Content from '../../../components/layouts/Content';
import MainButton from '../../../components/buttons/MainButton';
import SubButton from '../../../components/buttons/SubButton';
import TextField from '../../../components/form/TextField';
import ToggleSwitch from '../../../components/form/ToggleSwitch';
import Select from '../../../components/form/Select';

export default {
  components: {
    Content,
    MainButton,
    SubButton,
    TextField,
    ToggleSwitch,
    Select,
  },
  data() {
    return {
      single: {},
      form: {},
      select: {
        roles: [],
      },
    };
  },
  /**
   * ページ遷移前に必要な情報を読み込み+セット
   */
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.initForm();
      vm.fetch();
      vm.setItems();
    });
  },
  computed: {
    /**
     * 追加 or 更新
     */
    isCreate() {
      return !this.$route.params.id;
    },
    /**
     * ログイン中の自ユーザーかどうか
     */
    isMe() {
      return parseInt(this.$route.params.id) === this.$store.getters['auth/userId'];
    },
    /**
     * 管理者権限未満の権限かどうか
     */
    isNotRoleAdmin() {
      return !this.$store.getters['auth/userRoleControll'](ROLE_ADMIN);
    },
    /**
     * ページタイトル
     */
    getTitle() {
      if (this.isMe) return '自ユーザー情報編集';
      return 'ユーザー情報 ' + (this.isCreate ? '新規作成' : '編集');
    },
    /**
     * ボタンのテキスト
     */
    getButtonValue() {
      return this.isCreate ? '作成' : '更新';
    },
    getPostType() {
      return (postTypeId) =>
        this.form.permissions.filter((permission) => permission.post_type_id === postTypeId)[0];
    },
  },
  methods: {
    /**
     * フォームデータの初期化
     */
    initForm() {
      this.form = {
        id: null,
        name: '',
        login_id: '',
        role_id: null,
        email: '',
        password: '',
        is_enable: 1,
        permissions: this.$store.getters['page/postTypes'].map((postType) => ({
          post_type_id: postType.id,
          permission: false,
        })),
      };
    },
    /**
     * 情報の読み込み
     */
    async fetch() {
      this.single = {};
      const id = this.$route.params.id;

      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.get(API_URL + '/admin/users/' + id);

        if (response.status === OK) {
          if (response.data.single.permissions.length === 0) {
            response.data.single.permissions = this.$store.getters['page/postTypes'].map((postType) => ({
              admin_id: response.data.single.id,
              post_type_id: postType.id,
              permission: true,
            }));
          }

          this.single = { ...response.data.single };
          this.form = { ...response.data.single };
          const isForbidden =
            !this.$store.getters['auth/userRoleControll'](this.single.role.identifier) ||
            (!this.isMe && this.$store.getters['auth/userRole'] === ROLE_EDITOR);

          this.$store.commit('error/setForbidden', isForbidden);
          return false;
        }

        this.$store.dispatch('system/createLog', {
          response: response,
          message: ERROR_MESSAGES[response.status],
        });
      });
    },
    /**
     * 選択肢のセット
     */
    setItems() {
      this.select.roles = this.$store.getters['auth/roles'].filter((role) => {
        if (this.isCreate) return role.id >= this.$store.state.auth.user.role_id;

        if (this.isMe) return role.id === this.$store.state.auth.user.role_id;

        return role.id > this.$store.state.auth.user.role_id;
      });
    },
    /**
     * 登録
     */
    async storeOrUpdate() {
      await this.$store.dispatch('page/loading', async () => {
        this.$store.commit('error/setValidate', null);

        const response = this.isCreate
          ? await axios.post(API_URL + '/admin/users', this.form)
          : await axios.put(API_URL + '/admin/users/' + this.single.id, this.form);

        if (response.status === OK || response.status === CREATED) {
          if (response.data.permissions.length === 0) {
            response.data.permissions = this.$store.getters['page/postTypes'].map((postType) => ({
              admin_id: response.data.id,
              post_type_id: postType.id,
              permission: true,
            }));
          }

          this.single = { ...response.data };
          this.form = { ...response.data };
          if (this.isMe) this.$store.commit('auth/setUser', response.data);
          this.$store.dispatch('system/createLog', {
            response: response,
            message: 'ユーザー情報を' + this.getButtonValue + 'しました',
          });

          if (this.isCreate)
            this.$router.replace({
              name: 'user-edit',
              params: { id: this.single.id },
            });

          return false;
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
          this.$store.commit('error/setValidate', response.data.errors);
        }

        this.$store.dispatch('system/createLog', {
          response: response,
          message: ERROR_MESSAGES[response.status],
        });
      });
    },
  },
};
</script>
