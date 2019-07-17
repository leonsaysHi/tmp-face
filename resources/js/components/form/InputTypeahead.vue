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
        :data="apiOptions"
        v-model="typedSearch"
        :serializer="serializer"
        :placeholder="placeholder"
        @hit="$emit('input', $event)"
      />
      <div class="spinner" :class="{'-show': isBusy}"><fa-icon icon="spinner" size="lg" spin /></div>
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
      apiOptions: [],
      isBusy: false,
    };
  },
  created() {
    this.currentValue = this.value
  },
  methods: {
    async getSearchResults(query) {
      this.isBusy = true
      const results = await this.searchFunc(query)
      this.apiOptions = results
      this.isBusy = false
    }
  },
  watch: {
    typedSearch: _.debounce(function(query) {
      query = _.trim(query)
      if (query.length) {
        this.getSearchResults(query)
      }
      else {
        this.apiOptions = []
      }
    }, 500)
  },
};
</script>
