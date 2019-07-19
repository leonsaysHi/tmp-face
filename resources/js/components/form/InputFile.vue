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
    <b-progress v-if="isBusy" class="mt-2" :value="uploadedPerc" :max="100" show-progress animated />
  </div>
</template>

<script>
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
      file: null,
      title: null,
      uploadedPerc: 0,
      isBusy: false,
      isSuccess: null,
      isError: null,
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
  },
  methods: {
    reset() {
      this.file = null
      this.isBusy = false
      this.uploadedPerc = 0
      this.isSuccess = null
      this.isError = null
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
