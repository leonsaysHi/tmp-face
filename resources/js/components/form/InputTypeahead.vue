<template>
  <div class="input-typeahead">
    <template v-if="!searchFunc">
      <vue-bootstrap-typeahead  ref="typeahead"
        v-model="selectedOption"
        :data="options"
        :placeholder="placeholder"
        :serializer="serializer"
        @hit="$emit('input', $event)"
      />
    </template>
    <template v-else>
      <vue-bootstrap-typeahead ref="typeahead"
        :data="apiOptions"
        v-model="typedSearch"
        :serializer="serializer"
        :placeholder="placeholder"
        @hit="$emit('input', $event)"
      />
      <div class="spinner" :class="{'-show': isBusy}">
        <b-spinner small></b-spinner>
      </div>
    </template>
  </div>
</template>

<script>
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
import { isMobile } from 'mobile-device-detect';

export default {
  props: {
    value: {type: [String, Object]},
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
      selectedOption: null,
      typedSearch: '',
      apiOptions: [],
      isBusy: false,
    };
  },
  created() {
    this.selectedOption = this.value
  },
  methods: {
    async getSearchResults(query) {
      this.isBusy = true
      const results = await this.searchFunc(query)
      this.apiOptions = results
      this.isBusy = false
    },
    reset() {
      this.selectedOption = null
      this.$refs.typeahead.inputValue = ''
    }
  },
  watch: {
    value: function(v) {
      if (!v || v === '') {
        this.reset()
      }
    },
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
