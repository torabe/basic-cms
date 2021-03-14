<template>
  <v-card v-if="categories && Object.keys(categories).length !== 0">
    <v-card-title>
      <v-icon class="mr-1" v-text="'mdi-file-tree'" color="primary" />
      カテゴリ選択
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-row v-for="categoryType in postType.category_types" :key="categoryType.slug" dense>
          <v-col>
            <Select
              :label="categoryType.name"
              :items="
                categoryType.categories.flatMap((category) => {
                  return [
                    category,
                    ...category.children.map((child) => ({
                      ...child,
                      name: category.name + ' - ' + child.name,
                    })),
                  ];
                })
              "
              item-value="id"
              item-text="name"
              multiple
              :error-messages="$store.getters['error/validate'](`categories.${categoryType.slug}`)"
              v-model="categories[categoryType.slug]"
              v-if="categoryType.select === 'select' && categoryType.is_multiple"
            />

            <Select
              :label="categoryType.name"
              :items="
                categoryType.categories.flatMap((category) => {
                  return [
                    category,
                    ...category.children.map((child) => ({
                      ...child,
                      name: category.name + ' - ' + child.name,
                    })),
                  ];
                })
              "
              item-value="id"
              item-text="name"
              :error-messages="$store.getters['error/validate'](`categories.${categoryType.slug}`)"
              v-model="categories[categoryType.slug][0]"
              v-if="categoryType.select === 'select' && !categoryType.is_multiple"
            />

            <div v-if="categoryType.select === 'checkbox'">
              <div class="subtitle-1" v-if="categoryType.name !== ''">{{ categoryType.name }}</div>
              <v-messages
                class="error--text"
                :value="$store.getters['error/validate'](`categories.${categoryType.slug}`)"
                v-if="$store.getters['error/validate'](`categories.${categoryType.slug}`).length !== 0"
              />
              <v-treeview
                :items="categoryType.categories"
                selectable
                selection-type="independent"
                open-all
                v-model="categories[categoryType.slug]"
              ></v-treeview>
            </div>

            <RadioGroup
              :label="categoryType.name"
              :items="
                categoryType.categories.flatMap((category) => {
                  return [
                    {
                      value: category.id,
                      text: category.name,
                    },
                    ...category.children.map((child) => ({
                      value: child.id,
                      text: category.name + ' - ' + child.name,
                    })),
                  ];
                })
              "
              :error-messages="$store.getters['error/validate'](`categories.${categoryType.slug}`)"
              column
              v-model="categories[categoryType.slug][0]"
              v-if="categoryType.select === 'radio'"
            />
          </v-col>
        </v-row>
      </v-container>
    </v-card-text>
  </v-card>
</template>

<script>
import Select from '../../../components/form/Select';
import RadioGroup from '../../../components/form/RadioGroup';

export default {
  components: {
    Select,
    RadioGroup,
  },
  props: {
    postType: {
      type: Object,
      required: true,
      defalut: () => {},
    },
    value: {
      type: Object,
      required: false,
      defalut: () => {},
    },
  },
  data() {
    return {
      categories: this.value,
    };
  },
  watch: {
    value: {
      handler(newValue) {
        this.categories = newValue;
      },
      immediate: true,
    },
    categories: {
      handler(newValue) {
        this.$emit('input', newValue);
      },
      deep: true,
    },
  },
};
</script>
