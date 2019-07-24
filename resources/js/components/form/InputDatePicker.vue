<template>
  <div class="input-date-picker">
    <b-input
      v-if="isMobile"
      :class="['form-control', state == true ? 'is-valid' : state == false ? 'is-invalid' : '']"
      :type="type === 'datetime' ? 'datetime-local' : type"
      autocomplete="off"
      :name="name"
      :placeholder="placeholder"
      :disabled="disabled"
      :required="required"
      :state="state"
      :value="selectedDate"
      @input="onInput"
    />
    <template v-else>
      <datetime-picker
        :class="[state == true ? 'is-valid' : state == false ? 'is-invalid' : '']"
        :name="name"
        v-model="selectedDate"
        :required="required"
        :placeholder="placeholder"
        :disabled="disabled"
        :config="pickerConfig"
        @dp-change="onInput"
      />
      <fa-icon icon="calendar-alt" size="lg" class="icon" />
    </template>
  </div>
</template>

<script>
import { isMobile } from 'mobile-device-detect';

export default {
  props: {
    value: {type: String},
    name: {type: String},
    type: {type: String, default: 'date'},
    placeholder: {type: String, default: null},
    required: {type: Boolean},
    disabled: {type: Boolean, default: false},
    state: {type: Boolean, default: null},
  },
  components: {
    // documentation: https://github.com/ankurk91/vue-bootstrap-datetimepicker
    "datetime-picker": require("vue-bootstrap-datetimepicker")
  },
  data() {
    return {
      isMobile,
      selectedDate: null
    };
  },
  created() {
    this.selectedDate = this.value
  },
  computed: {
    isDateTime() { return this.type && this.type === 'datetime' },
    displayFormat() { return this.isDateTime ? "DD-MMM-YYYY HH:mm" : "DD-MMM-YYYY" },
    valueFormat() { return this.isDateTime ? "YYYY-MM-DDTHH:mm" : "YYYY-MM-DD"},
    pickerConfig () {
      return  {
        format: this.displayFormat,
        useCurrent: false,
      }
    },
  },
  methods: {
    onInput(value) {
      if (this.isMobile || value.date) {
        const emitted = this.isMobile ? moment(value).format(this.valueFormat) : moment(value.date, this.displayFormat).format(this.valueFormat)
        this.$emit('input', emitted );
      }
    },
  },
  watch: {
    value: function(v) {
      if (!v || v === '') {
        this.selectedDate = null
      }
    }
  },
};
</script>
