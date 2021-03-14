<template>
  <v-app>
    <Progress />
    <Nav v-if="isLogin" />
    <Header v-if="isLogin" />
    <v-main>
      <SystemAlerts v-if="isLogin" />
      <SystemUpdate />
      <Forbidden v-if="isForbidden" />
      <router-view v-else />
    </v-main>
    <Footer />
  </v-app>
</template>

<script>
import Nav from './components/layouts/Nav';
import Header from './components/layouts/Header';
import Footer from './components/layouts/Footer';
import Progress from './components/layouts/Progress';
import SystemAlerts from './components/system/Alerts';
import SystemUpdate from './components/system/Update';
import Forbidden from './pages/403/App';

export default {
  components: {
    Nav,
    Header,
    Footer,
    Progress,
    SystemAlerts,
    SystemUpdate,
    Forbidden,
  },
  computed: {
    isLogin() {
      return this.$store.getters['auth/check'];
    },
    isForbidden() {
      return this.$store.getters['error/forbidden'];
    },
  },
};
</script>
