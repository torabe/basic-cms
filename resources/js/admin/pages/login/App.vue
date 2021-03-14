<template>
  <v-container class="d-flex flex-column justify-center">
    <h1 class="primary--text mb-2 align-self-center">{{ appName }}</h1>
    <v-row class="flex-grow-0" justify="center">
      <v-col cols="12" sm="8" md="6">
        <v-form ref="login" @submit.prevent="login">
          <v-card>
            <v-card-text>
              <TextField
                icon="mdi-account-circle"
                label="Login ID"
                :error-messages="$store.getters['error/validate']('login_id')"
                v-model="form.login_id"
              />
              <TextField
                icon="mdi-lock"
                type="password"
                label="Password"
                :error-messages="$store.getters['error/validate']('password')"
                v-model="form.password"
              />
            </v-card-text>
            <v-card-actions>
              <MainButton type="submit">Login</MainButton>
            </v-card-actions>
          </v-card>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { APP_NAME } from '../../../config/const';

import MainButton from '../../components/buttons/MainButton';
import TextField from '../../components/form/TextField';

export default {
  components: {
    MainButton,
    TextField,
  },
  data() {
    return {
      appName: APP_NAME,
      form: {
        login_id: '',
        password: '',
      },
    };
  },
  computed: {
    /**
     * ステータスコード取得
     */
    getApiStatus() {
      return this.$store.state.auth.apiStatus;
    },
  },
  methods: {
    /**
     * ログイン
     */
    async login() {
      await this.$store.dispatch('page/loading', async () => {
        await this.$store.dispatch('auth/login', this.form);

        if (!this.getApiStatus) return;

        const referer = this.$store.getters['auth/referer'];
        this.$store.commit('auth/setReferer', null);

        const route = {
          name: referer !== null ? referer.name : 'dashboard',
        };
        if (referer && referer.params) route.params = referer.params;
        this.$router.push(route);
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.container {
  height: 100%;
}
.v-input {
  &:not(:first-child) {
    margin-top: 10px;
  }
}
</style>
