<template>
  <div class="input-file">
    <div class="d-flex">
      <b-input-group class="flex-grow-1">
        <b-file
          v-model="file"
          :accept="accept"
          :multiple="multiple"
          :disabled="disableInput"
          :placeholder="placeholder"
          :state="isError ? false : isSuccess ? true : null"
        />
        <fa-icon icon="upload" class="icon" />
      </b-input-group>
      <b-button v-if="showReset" :variant="isError ? 'danger' : 'success'" @click="reset()" class="ml-1">Reset</b-button>
      <b-button v-else-if="showTrigger" :disabled="disableTrigger" :variant="disableTrigger ? 'secondary' : 'primary'" @click="upload()" class="ml-1">Upload</b-button>
    </div>
    <b-collapse v-model="showProgress" :id="'input-file-progress-' + _uid">
      <div class="pt-1">
        <b-progress :value="isSuccess ? 100 : isError ? 0 : uploadedPerc" :max="100" show-progress animated />
      </div>
    </b-collapse>
  </div>
</template>

<script>
const resetData = {
  file: null,
  isBusy: false,
  isSuccess: null,
  isError: null,
  uploadedPerc: 0,
}
export default {
  props: {
    value: { type: Object, default: null },
    placeholder: { type: String, default: null },
    disabled: { type: Boolean, default: false },
    api: { type: String, default: '/api/upload' },
    accept: { type: String, default: '.doc, .docx, .rtf, .txt, .csv, .sas, .xls, .xlsx, .pdf, .mp4, .jpeg' },
    multiple: { type: Boolean, default: false },
    showTrigger: { type: Boolean, default: true },
  },
  data() {
    return {
      ...resetData
    }
  },
  created() {
    this.file = this.value
  },
  computed: {
    showReset() {
      return this.isSuccess || this.isError
    },
    disableInput() {
      return this.disabled || this.showReset || this.isBusy
    },
    disableTrigger() {
      return this.disabled || !this.file || this.isBusy
    },
    showProgress: {
      get: function() { return this.isBusy || this.showReset },
  		set: function(newValue) { }
    },
  },
  methods: {
    reset() {
      Object.assign(this.$data, resetData)
    },
    getFormData() {
      let formData = new FormData()
      formData.append('file', this.file)
      formData.append('fileType', this.type)
      formData.append('fileName', this.file.name)
      return formData
    },
    async upload() {
      try {
        this.isBusy = true;
        let res = (await axios.post(
            this.api,
            this.getFormData(),
            {
              onUploadProgress: function (progressEvent) {
                this.uploadedPerc = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total))
              }.bind(this),
            }
          )).data;
        this.isBusy = false
        this.isSuccess = true
        this.$root.$emit('success', res);
      } catch (e) {
        this.isBusy = false
        this.isError = true
        this.$root.$emit('error', e);
      }
    },
  }
}
</script>
