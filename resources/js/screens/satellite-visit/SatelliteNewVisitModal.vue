<template>
  <div>
    <slot v-bind:toggle="toggle"></slot>
    <b-modal
      v-model="show"
      id="modal-new-visit"
      size="lg"
      title="Assign Satellite Specialist"
      cancel-variant="outline-primary"
    >
      <div class="d-flex align-items-end">
        <div class="d-flex flex-grow-1">
          <div role="group" class="form-group flex-grow-1 pr-2">
            <label for="input-date" class="d-block">Visit Week</label>
            <input-date-picker
              id="input-date"
              v-model="form.values.date"
              placeholder="Select"
            />
          </div>
          <b-form-group id="input-group-type" class="flex-grow-1 flex-shrink-0 pr-2" label="Visit Type" label-for="input-type">
            <b-form-select
              id="input-type"
              v-model="form.values.type"
              :options="form.typeOptions"
            ></b-form-select>
          </b-form-group>
          <b-form-group id="input-group-specialist" class="flex-grow-1 pr-2" label="Visitor Name" label-for="input-specialist">
            <input-typeahead
              id="input-specialist"
              v-model="form.values.specialist"
              :options="form.specialistOptions"
              placeholder="Specialist Name"
            />
          </b-form-group>
        </div>
        <b-button variant="primary" class="px-4 mb-3">Search</b-button>
      </div>
      <hr class="primary" />
      [list]
    </b-modal>
  </div>
</template>

<script>
export default {
  data() {
    return {
      show: false,
      form: {
        values: {
          date: null,
          type: null,
          specialist: null,
        },
        specialistOptions: [
          { text: "User1", value: "user-1" },
          { text: "User2", value: "user-2" },
          { text: "User3", value: "user-3" },
        ],
        typeOptions: [
          { text: "Type 1", value: "type-1" },
          { text: "Type 2", value: "type-2" },
          { text: "Type 3", value: "type-3" },
        ],
      },
    }
  },
  methods: {
    toggle() {
      console.log(this.show)
      this.show = !this.show
    }
  }
}
</script>
