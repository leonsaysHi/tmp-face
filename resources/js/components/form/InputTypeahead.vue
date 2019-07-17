<template>
  <div class="input-typeahead">
    <vue-bootstrap-typeahead v-if="!searchFunc"
      v-model="value"
      :data="options"
      :placeholder="placeholder"
      @hit="$emit('input', value)"
    />
    <template v-else>
      <vue-bootstrap-typeahead
        :data="options"
        v-model="typedSearch"
        :serializer="s => s.text"
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
    searchFunc: {type: Function, default: null},
    options: {type: Array, default: null},
  },
  components: {
    // documentation: https://github.com/alexurquhart/vue-bootstrap-typeahead
    VueBootstrapTypeahead
  },
  data() {
    return {
    };
  },
  watch: {
    typedSearch: _.debounce(function(addr) { this.options = this.searchFunc(addr) }, 500)
  },
};
</script>
