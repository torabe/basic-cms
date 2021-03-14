<template>
  <Content :title="postType.name + ' ' + title" :icon="postType.admin_icon">
    <v-form ref="edit" @submit.prevent="storeOrUpdate">
      <v-row>
        <v-spacer></v-spacer>
        <v-col cols="auto">
          <SubButton :to="{ name: 'index', params: { slug: postType.slug } }">一覧に戻る</SubButton>
        </v-col>
      </v-row>
      <Edit
        :post-type="postType"
        :single="single"
        :action="action"
        :is-create="isCreate"
        v-model="form"
        @storeOrUpdate="storeOrUpdate"
        v-if="!postType.is_customize"
      />
    </v-form>
  </Content>
</template>

<script>
import axios from 'axios';
import { API_URL } from '../../../../config/const';
import { OK, CREATED, UNPROCESSABLE_ENTITY, ERROR_MESSAGES } from '../../../../modules/util';

import Content from '../../../components/layouts/Content';
import SubButton from '../../../components/buttons/SubButton';

import Edit from '../components/Edit';

export default {
  components: {
    Content,
    SubButton,
    Edit,
  },
  /**
   * stateのセット
   *
   * @return void
   */
  data() {
    const postType = this.$store.getters['page/postType'](this.$route.params.slug);
    return {
      postType: postType,
      single: {},
      form: {},
      title: this.$route.name === 'create' ? '新規登録' : '更新',
      action: this.$route.name === 'create' ? '登録' : '更新',
    };
  },
  beforeRouteEnter(to, from, next) {
    /**
     * ページ遷移前に必要な情報を読み込み+セット
     *
     * @param {any} (vm)
     * @return void
     */
    next((vm) => {
      vm.initForm();
      vm.fetch();
    });
  },
  /**
   * ルーティングのパラメータの変更を検知する
   *
   * @param {any} to
   * @param {any} from
   * @param {any} next
   * @return void
   */
  beforeRouteUpdate(to, from, next) {
    if (to.path !== from.path) {
      this.postType = this.$store.getters['page/postType'](to.params.slug);
      this.initForm();
      this.fetch();
    }
    next();
  },
  computed: {
    /**
     * 新規登録 or 更新
     *
     * @return void
     */
    isCreate() {
      return this.$route.name === 'create';
    },
  },
  methods: {
    /**
     * フォームデータの初期化
     *
     * @return void
     */
    initForm() {
      const categories = {};
      this.postType.category_types.forEach((categoryType) => {
        categories[categoryType.slug] = [];
      });

      const customFields = this.postType.custom_field_metas.map((meta) => ({
        meta_id: meta.id,
        value: meta.type === 'link' ? { url: null, text: null, target: null } : null,
        meta: meta,
      }));

      this.form = {
        id: null,
        post_type_id: this.postType.id,
        publish_at: '',
        unpublish_at: '',
        title: '',
        slug: '',
        description: '',
        admin_id: this.$store.getters['auth/userId'],
        sort: 0,
        is_enable: 1,
        categories: categories,
        custom_fields: customFields,
      };
    },
    setForm(data) {
      const categories = {};
      this.postType.category_types.forEach((categoryType) => {
        categories[categoryType.slug] = data.categories
          .filter((category) => category.category_type_id === categoryType.id)
          .map((category) => category.id);
      });

      const customFields = this.postType.custom_field_metas.map((meta) => {
        const [field] = data.custom_fields.filter((f) => f.meta_id === meta.id);

        return field
          ? field
          : {
              meta_id: meta.id,
              value: meta.type === 'link' ? { url: null, text: null, target: null } : null,
              meta: meta,
            };
      });

      this.form = { ...data, categories: categories, custom_fields: customFields };
    },
    /**
     * 情報の読み込み
     *
     * @return void
     */
    async fetch() {
      this.single = {};
      const id = this.$route.params.id ? this.$route.params.id : 0;

      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.get(API_URL + '/admin/' + this.postType.slug + '/posts/' + id);

        if (response.status === OK) {
          if (this.isCreate) return false;

          this.single = { ...response.data.single };
          this.setForm(response.data.single);

          return false;
        }

        this.$store.dispatch('system/createLog', {
          response: response,
          message: ERROR_MESSAGES[response.status],
        });
      });
    },
    /**
     * 登録 or 更新
     *
     * @return void
     */
    async storeOrUpdate() {
      await this.$store.dispatch('page/loading', async () => {
        this.$store.commit('error/setValidate', null);

        const form = new FormData();
        Object.keys(this.form).forEach((field) => {
          switch (field) {
            case 'categories':
              Object.keys(this.form[field]).forEach((slug) => {
                this.form[field][slug].forEach((id) => {
                  form.append(`${field}[${slug}][]`, id);
                });
              });
              return;
            case 'custom_fields':
              this.form[field].forEach((customField, index) => {
                Object.keys(customField).forEach((column) => {
                  if (customField.meta.type === 'link' && column === 'value') {
                    Object.keys(customField[column]).forEach((key) => {
                      form.append(`${field}[${index}][${column}][${key}]`, customField[column][key]);
                    });
                    return;
                  }
                  form.append(`${field}[${index}][${column}]`, customField[column]);
                });
              });
              return;
            default:
              form.append(field, this.form[field]);
              return;
          }
        });

        const response = this.isCreate
          ? await axios.post(API_URL + '/admin/' + this.postType.slug + '/posts', form)
          : await axios.post(API_URL + '/admin/' + this.postType.slug + '/posts/' + this.single.id, form, {
              headers: {
                'Content-Type': 'multipart/form-data',
                'X-HTTP-Method-Override': 'PUT',
              },
            });

        if (response.status === OK || response.status === CREATED) {
          this.single = { ...response.data };
          this.setForm(response.data);

          this.$store.dispatch('system/createLog', {
            response: response,
            message: this.postType.name + 'を' + this.action + 'しました',
          });

          if (this.isCreate)
            this.$router.replace({
              name: 'edit',
              params: { slug: this.postType.slug, id: this.single.id },
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
