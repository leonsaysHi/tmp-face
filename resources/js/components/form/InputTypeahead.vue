<template>
  <div class="input-typeahead">
    <vue-bootstrap-typeahead v-if="!searchFunc"
      v-model="currentValue"
      :data="options"
      :placeholder="placeholder"
      :serializer="serializer"
      @hit="$emit('input', $event)"
    />
    <template v-else>
      <vue-bootstrap-typeahead
        :data="options"
        v-model="typedSearch"
        :serializer="serializer"
        :placeholder="placeholder"
        @hit="$emit('input', $event)"
      />
    </template>
  </div>
</template>

<script>
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
import { isMobile } from 'mobile-device-detect';

export default {
  props: {
    value: {type: String},
    placeholder: {type: String, default: null},
    serializer: {type: Function, default: s => s.text},
    searchFunc: {type: Function, default: null},
    options: {type: Array, default: ()=>([])},
  },
  components: {
    // documentation: https://github.com/alexurquhart/vue-bootstrap-typeahead
    VueBootstrapTypeahead
  },
  data() {
    return {
      currentValue: null,
      typedSearch: '',
    };
  },
  created() {
    this.currentValue = this.value
  },
  methods: {
    async getSearchResults(query) {
      const results = await this.searchFunc(query)
      this.options = results
    }
  },
  watch: {
    typedSearch: _.debounce(function(query) {
      this.getSearchResults(query)
    }, 500)
  },
};
</script>
