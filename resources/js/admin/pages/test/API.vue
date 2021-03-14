<template>
  <Content :title="'API テスト'">
    <v-form ref="user-edit" @submit.prevent="request">
      <v-row>
        <v-col>
          <v-card>
            <v-card-title>リクエスト</v-card-title>
            <v-card-text>
              <v-alert type="error" v-if="hasError">{{ error }}</v-alert>
              <TextField label="API URL" v-model="form.api_url" :error="hasError" />
              <Textarea label="パラメータ" hint="JSON文字列で入力" v-model="form.params" :error="hasError" />
            </v-card-text>
            <v-card-actions>
              <MainButton type="submit">リクエスト</MainButton>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
      <v-row v-if="status_code !== ''">
        <v-col>
          <v-card>
            <v-card-title>レスポンス</v-card-title>
            <v-card-text>
              <dl class="d-flex flex-row">
                <dt>ステータスコード：</dt>
                <dd>{{ status_code }}</dd>
              </dl>
              <Textarea label="結果" v-model="api_result" />
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-form>
  </Content>
</template>

<script>
import axios from 'axios';
import { SITE_URL, API_URL } from '../../../config/const';
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
import MainButton from '../../components/buttons/MainButton';
import TextField from '../../components/form/TextField';
import Textarea from '../../components/form/Textarea';

export default {
  components: {
    Content,
    MainButton,
    TextField,
    Textarea,
  },
  data() {
    return {
      form: {
        api_url: API_URL,
        params: '{}',
      },
      api_result: '',
      status_code: '',
      error: null,
    };
  },
  computed: {
    hasError() {
      return this.error !== null;
    },
  },
  methods: {
    async request() {
      await this.$store.dispatch('page/loading', async () => {
        try {
          this.error = null;
          this.$store.commit('error/setValidate', null);
          const params = JSON.parse(this.form.params);
          const response = await axios.post(this.form.api_url, params);
          this.status_code = response.status;
          this.api_result = JSON.stringify(response.data);
        } catch (e) {
          this.error = e;
        }
      });
    },
  },
};
</script>
