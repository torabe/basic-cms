<template>
  <Content title="ユーザー管理" :icon="'mdi-account-details'">
    <v-row v-if="isRoleAdmin">
      <v-spacer />
      <v-col cols="auto">
        <ActionButton :to="{ name: 'user-create' }">新規作成</ActionButton>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-card>
          <v-data-table
            calculate-widths
            :headers="headers"
            :items="items"
            :footer-props="{
              'items-per-page-options': [10, 20, 50, 100, -1],
              showFirstLastPage: true,
            }"
            :options.sync="options"
            :loading="$store.getters['page/loading']"
            loading-text="Loading..."
            :no-data-text="noDataText"
          >
            <template v-slot:item.name="{ item }">
              <router-link :to="{ name: 'user-edit', params: { id: item.id } }" v-if="showCols(item)">{{
                item.name
              }}</router-link>
              <span v-else>{{ item.name }}</span>
              ({{ item.login_id }})
            </template>

            <template v-slot:item.is_enable="{ item }">
              <ToggleSwitch
                :label="item.is_enable ? '利用中' : '停止中'"
                :error-messages="$store.getters['error/validate']('is_enable')"
                :readonly="!showCols(item)"
                @event="update(item)"
                v-model="item.is_enable"
                v-if="!isMe(item)"
              />
            </template>

            <template v-slot:item.action="{ item }">
              <v-row v-if="showCols(item)" dense>
                <v-col cols="auto">
                  <MainButton @click="$router.push({ name: 'user-edit', params: { id: item.id } })">
                    <v-icon small v-text="'mdi-pencil'" />編集
                  </MainButton>
                </v-col>
                <v-col cols="auto" v-if="!isMe(item)">
                  <DeleteButton @click="destroy(item)">
                    <v-icon small v-text="'mdi-delete'" />削除
                  </DeleteButton>
                </v-col>
              </v-row>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </Content>
</template>

<script>
import axios from 'axios';
import { API_URL } from '../../../config/const';
import {
  OK,
  CREATED,
  UNPROCESSABLE_ENTITY,
  ERROR_MESSAGES,
  ROLE_DEVELOPER,
  ROLE_ADMIN,
  ROLE_EDITOR,
  ROLE_CONTRIBUTOR,
} from '../../../modules/util';

import Content from '../../components/layouts/Content';
import ActionButton from '../../components/buttons/ActionButton';
import DeleteButton from '../../components/buttons/DeleteButton';
import MainButton from '../../components/buttons/MainButton';
import ToggleSwitch from '../../components/form/ToggleSwitch';

export default {
  components: {
    Content,
    ActionButton,
    DeleteButton,
    MainButton,
    ToggleSwitch,
  },
  data() {
    return {
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'ユーザー名', value: 'name' },
        { text: '権限', value: 'role.name' },
        { text: 'メールアドレス', value: 'email' },
        { text: '状態', value: 'is_enable' },
        { text: '操作', value: 'action', sortable: false, width: 200 },
      ],
      options: {
        page: 1,
        itemsPerPage: 10,
        sortBy: ['id'],
        sortDesc: [false],
      },
      items: [],
      noDataText: 'ユーザーの登録はありません',
    };
  },
  mounted() {
    this.fetch();
  },
  computed: {
    showCols() {
      return (item) => {
        const roleControll = this.$store.getters['auth/userRoleControll'](item.role.identifier);
        const isMe = this.isMe(item);
        const isEditor = this.$store.getters['auth/userRole'] === ROLE_EDITOR;
        return roleControll && !(!isMe && isEditor);
      };
    },
    isRoleAdmin() {
      return this.$store.getters['auth/userRoleControll'](ROLE_ADMIN);
    },
    isMe() {
      return (item) => {
        return item.id === this.$store.getters['auth/userId'];
      };
    },
  },
  methods: {
    async fetch() {
      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.get(API_URL + '/admin/users', null);

        if (response.status === OK) {
          this.items = response.data.items;

          return false;
        }

        this.$store.dispatch(
          'system/createLog',
          { response: response, message: ERROR_MESSAGES[response.status] },
          { root: true }
        );
      });
    },
    async update(item) {
      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.put(API_URL + '/admin/users/' + item.id, item);

        if (response.status === OK) {
          this.items = this.items.map((item) => (item.id === response.data.id ? response.data : item));

          this.$store.dispatch('system/createLog', {
            response: response,
            message:
              'ユーザー「' +
              response.data.name +
              '」を' +
              (response.data.is_enable ? '有効化' : '無効化') +
              'しました',
          });

          return false;
        }

        this.$store.dispatch(
          'system/createLog',
          { response: response, message: ERROR_MESSAGES[response.status] },
          { root: true }
        );
      });
    },
    async destroy(item) {
      if (!confirm('ユーザー「' + item.name + '」を削除します。\nよろしいですか？')) return false;

      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.delete(API_URL + '/admin/users/' + item.id);

        if (response.status === OK) {
          const [destroyedItem] = this.items.filter((item) => item.id === response.data);
          this.items = this.items.filter((item) => item.id !== response.data);

          this.$store.dispatch('system/createLog', {
            response: response,
            message: 'ユーザー「' + destroyedItem.name + '」を削除しました',
          });
          return false;
        }

        this.$store.dispatch(
          'system/createLog',
          { response: response, message: ERROR_MESSAGES[response.status] },
          { root: true }
        );
      });
    },
  },
};
</script>
