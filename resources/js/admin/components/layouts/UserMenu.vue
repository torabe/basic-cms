<template>
  <v-menu offset-y :close-on-content-click="false">
    <template v-slot:activator="{ on: menu, attrs }">
      <v-tooltip bottom>
        <template v-slot:activator="{ on: tooltip }">
          <v-btn text v-bind="attrs" v-on="{ ...tooltip, ...menu }">
            <v-icon v-text="'mdi-account'" />
          </v-btn>
        </template>
        <span>{{ userName }}</span>
      </v-tooltip>
    </template>
    <v-card>
      <v-card-subtitle>{{ userName }}</v-card-subtitle>
      <v-list>
        <v-list-item
          dense
          :to="{ name: 'user-edit', params: { id: this.$store.getters['auth/userId'] } }"
        >ユーザー情報編集</v-list-item>
      </v-list>
      <v-card-actions>
        <v-spacer></v-spacer>
        <SubButton @click="logout">Logout</SubButton>
      </v-card-actions>
    </v-card>
  </v-menu>
</template>

<script>
import SubButton from '../buttons/SubButton';

export default {
  components: {
    SubButton,
  },
  computed: {
    userName() {
      return this.$store.getters['auth/userName'];
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
