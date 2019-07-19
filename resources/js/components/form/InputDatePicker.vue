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
      :value="value"
      @input="onInput"
    />
    <template v-else>
      <datetime-picker
        :class="[state == true ? 'is-valid' : state == false ? 'is-invalid' : '']"
        :name="name"
        v-model="formattedValue"
        :required="required"
        :placeholder="placeholder"
        :disabled="disabled"
        @dp-change="onInput"
        :config="pickerConfig"
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
      formattedValue: null
    };
  },
  created(){
    this.formattedValue = moment(this.value, this.valueFormat).format(this.displayFormat);
  },
  computed: {
    isDateTime() { return this.type && this.type === 'datetime' },
    displayFormat() { return this.isDateTime ? "DD-MMM-YYYY HH:mm" : "DD-MMM-YYYY" },
    valueFormat() { return this.isDateTime ? "YYYY-MM-DDTHH:mm" : "YYYY-MM-DD"},
    pickerConfig () {
      return  {
        format: this.displayFormat,
        sideBySide: true,
        useCurrent: false,
        widgetPositioning: {
          vertical: 'bottom',
        },
      }
    }
  },
  methods: {
    onInput(value) {
      this.$emit('input', isMobile ? moment(value).format(this.valueFormat) : moment(this.formattedValue, this.displayFormat).format(this.valueFormat) );
    },
  }
};
</script>
