<template>
  <v-scroll-x-reverse-transition class="system-alerts" group leave-absolute v-show="alerts.length !== 0">
    <v-alert v-for="alert in alerts" :key="alert.uuid" :type="getType(alert.status)">{{
      alert.message
    }}</v-alert>
  </v-scroll-x-reverse-transition>
</template>

<script>
import dayjs from 'dayjs';
dayjs.locale('ja');
import {
  OK,
  CREATED,
  UNAUTHORIZED,
  NOT_FOUND,
  UNPROCESSABLE_ENTITY,
  INTERNAL_SERVER_ERROR,
} from '../../../modules/util';

export default {
  data() {
    return {
      alerts: [],
    };
  },
  mounted() {
    setInterval(() => {
      this.getAlerts();
    }, 1000);
  },
  computed: {
    getType() {
      return (status) => {
        switch (status) {
          case OK:
          case CREATED:
            return 'success';
          case NOT_FOUND:
          case UNAUTHORIZED:
          case UNPROCESSABLE_ENTITY:
          case INTERNAL_SERVER_ERROR:
            return 'error';
          default:
            return 'info';
        }
      };
    },
  },
  methods: {
    getAlerts() {
      this.alerts = this.$store.state.system.logs
        .filter((log) => {
          const diff = dayjs().diff(log.date, 'second');
          return log.message !== null && diff < 5;
        })
        .filter((log, index) => index <= 2);
    },
  },
};
</script>

<style lang="scss" scoped>
.system-alerts {
  position: fixed;
  bottom: 60px;
  right: 12px;
  //transform: translate(-50%, 0);
  z-index: 10;
}
</style>
