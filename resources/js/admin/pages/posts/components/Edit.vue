<template>
  <v-row>
    <v-col cols="12" md="4" order="1" order-md="2">
      <v-card>
        <v-card-title>
          <v-icon class="mr-1" v-text="'mdi-information-outline'" color="primary" />
          基本情報
        </v-card-title>
        <v-card-subtitle v-if="!isCreate && single.author"> 著者：{{ single.author.name }} </v-card-subtitle>
        <v-card-text>
          <v-row dense>
            <v-col>
              <ActionButton @click="preview()" v-if="!isCreate">プレビュー</ActionButton>
              <ToggleSwitch
                :label="form.is_enable ? '公開' : '非公開'"
                :error-messages="$store.getters['error/validate']('is_enable')"
                v-model="form.is_enable"
              />
              <DateTimePicker
                label="公開日"
                hint="※必須入力"
                :error-messages="$store.getters['error/validate']('publish_at')"
                v-model="form.publish_at"
              />
              <DateTimePicker
                label="公開終了日"
                :error-messages="$store.getters['error/validate']('unpublish_at')"
                v-model="form.unpublish_at"
              />
              <TextField
                label="スラッグ"
                :error-messages="$store.getters['error/validate']('slug')"
                hint="半角英数字 URLに使用されます※必須入力 "
                v-model="form.slug"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <Textarea
                label="概要"
                :error-messages="$store.getters['error/validate']('description')"
                v-model="form.description"
              />
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <MainButton type="submit">{{ action }}</MainButton>
        </v-card-actions>
      </v-card>

      <Categories :post-type="postType" v-model="form.categories" />
    </v-col>

    <v-col cols="12" md="8" order="2" order-md="1">
      <v-card>
        <v-card-title>
          <v-icon class="mr-1" v-text="'mdi-text-box-outline'" color="primary" />
          コンテンツ
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col>
                <TextField
                  label="タイトル"
                  hint="※必須入力"
                  :error-messages="$store.getters['error/validate']('title')"
                  v-model="form.title"
                />
              </v-col>
            </v-row>

            <CustomFields :post-type="postType" v-model="form.custom_fields" />
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <MainButton type="submit">{{ action }}</MainButton>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { APP_URL } from '../../../../config/const';

import ActionButton from '../../../components/buttons/ActionButton';
import MainButton from '../../../components/buttons/MainButton';

import DateTimePicker from '../../../components/form/DateTimePicker';
import TextField from '../../../components/form/TextField';
import Textarea from '../../../components/form/Textarea';
import ToggleSwitch from '../../../components/form/ToggleSwitch';

import Categories from '../components/Categories';
import CustomFields from '../components/CustomFields';

export default {
  components: {
    ActionButton,
    MainButton,
    DateTimePicker,
    TextField,
    Textarea,
    ToggleSwitch,
    Categories,
    CustomFields,
  },
  props: {
    postType: {
      type: Object,
      required: true,
      defalut: () => {},
    },
    value: {
      type: Object,
      required: true,
      defalut: () => {},
    },
    single: {
      type: Object,
      required: true,
      defalut: () => {},
    },
    action: {
      type: String,
      required: true,
      default: '',
    },
    isCreate: {
      type: Boolean,
      required: true,
      default: true,
    },
  },
  data() {
    return {
      form: this.value,
    };
  },
  watch: {
    value: {
      handler(newValue) {
        this.form = newValue;
      },
      immediate: true,
    },
    items: {
      handler(newValue) {
        this.$emit('input', newValue);
      },
      deep: true,
    },
  },
  methods: {
    storeOrUpdate() {
      this.$emit('storeOrUpdate');
    },
    /**
     * プレビュー
     *
     * @return void
     */
    preview() {
      open(APP_URL + '/' + this.postType.slug + '/' + this.single.slug + '?preview', '_blank');
    },
  },
};
</script>


<style lang="scss" scoped>
.v-card {
  &:not(:first-child) {
    margin-top: 24px;
  }
}
</style>
