<template>
  <Content>
    <v-alert type="error">このページへのアクセスは許可されていません。</v-alert>
  </Content>
</template>

<script>
import Content from '../../components/layouts/Content';

export default {
  components: {
    Content,
  },
};
</script>
