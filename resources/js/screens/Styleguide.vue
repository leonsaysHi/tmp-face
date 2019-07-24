<template>
  <div class="styleguide">
    <div class="styleguide__block">
      <h1>h1. Bootstrap heading</h1>
      <h2>h2. Bootstrap heading</h2>
      <h3>h3. Bootstrap heading</h3>
      <h4>h4. Bootstrap heading</h4>
      <h5>h5. Bootstrap heading</h5>
      <h6>h6. Bootstrap heading</h6>
    </div>
    <div class="styleguide__block">
      <b-button variant="primary">Primary</b-button>
      <b-button variant="outline-primary">Outline primary</b-button>
      <b-button variant="secondary">Secondary</b-button>
      <b-button variant="outline-secondary">Outline secondary</b-button>
      <b-button variant="success">Success</b-button>
      <b-button variant="info">info</b-button>
      <b-button variant="danger">Danger</b-button>
      <b-link>Link</b-link>
    </div>
    <div class="styleguide__block">
      <hr />
      <hr class="primary" />
      <hr class="secondary" />
      <hr class="lg" />
      <hr class="primary lg" />
      <hr class="secondary lg" />
    </div>
    <div class="styleguide__block">
      <fa-icon icon="calendar-alt" />
      <fa-icon icon="check" />
      <fa-icon icon="plus-square" size="lg" />
      <fa-icon icon="download" size="2x" />
      <fa-icon icon="upload" size="2x" />
      <fa-icon icon="user-alt" size="3x" />
      <fa-icon icon="ellipsis-h" size="lg" />
      <fa-icon icon="eye" size="lg" />
      <fa-icon icon="eye-slash" size="lg" />
      <fa-icon icon="caret-down" size="lg" />
      <fa-icon icon="caret-up" size="lg" />
      <fa-icon icon="trash" size="lg" />
    </div>
    <div class="styleguide__block">
      <div class="d-flex justify-content-between mb-3">
        <h3>Screen title</h3>
        <div class="d-flex align-items-start">
          <b-button variant="outline-primary" class="mr-3">Extra action</b-button>
          <div>
            <div>Extra infos</div>
            <div class="h4">Extra status</div>
          </div>
        </div>
      </div>
    </div>
    <div class="styleguide__block">
      <h6>Speaker Basic Profile</h6>
      <b-row>
        <b-col>
          <small>Speaker Name:</small>
          <div class="lead">Speaker name</div>
        </b-col>
        <b-col>
          <small>Speaker Level:</small>
          <div class="lead">Senior Speaker</div>
        </b-col>
        <b-col>
          <small>Speaker Type:</small>
          <div class="lead">Medical Professional / Government</div>
        </b-col>
        <b-col>
          <small>City:</small>
          <div class="lead">Dalian - Liaoning Province</div>
        </b-col>
        <div class="w-100 my-2"></div>
        <b-col>
          <small>ID Type:</small>
          <div class="lead">- -</div>
        </b-col>
        <b-col>
          <small>ID Number:</small>
          <div class="lead text-primary">123456789</div>
        </b-col>
        <b-col>
          <small>Title:</small>
          <div class="lead">Senior Doctor</div>
        </b-col>
        <b-col>
          <small>Specialization:</small>
          <div class="lead">Psycology</div>
        </b-col>
        <hr class="secondary w-100 my-3">
        <b-col>
          <small>Event Agenda:</small>
          <div class="lead">Event 1: ###</div>
          <div class="lead">Event 2: ###</div>
          <div class="lead">Event 3: ###</div>
        </b-col>
        <hr class="primary w-100 my-3">
        <b-col>
          <small>Comment:</small>
          <div class="lead">- -</div>
        </b-col>
        <hr class="w-100 my-3">
      </b-row>
    </div>
    <div class="styleguide__block">
      <b-card header="Card title">
        <b-card-text>Card text: faucibus dolor eget est dictum blandit. Integer tempor tempor vulputate. Pellentesque malesuada consequat neque a maximus. Integer pharetra quis felis sit amet pharetra. Phasellus dapibus consectetur est convallis imperdiet. Fusce orci ligula, hendrerit sed massa sit amet, scelerisque laoreet nulla. In hac habitasse platea dictumst. Maecenas at lacinia augue. Nulla ut tempus nisl. Etiam ornare purus sed urna laoreet elementum. Nunc in eros et ex blandit vulputate in vel justo. Maecenas gravida cursus nulla, vitae fermentum tortor sodales ut. Aenean pulvinar vestibulum orci, sed aliquam risus. In a orci lacus.</b-card-text>
      </b-card>
    </div>
    <div class="styleguide__block">

      <b-form-group
        id="input-group-1"
        label="Email address:"
        label-for="input-1"
        description="We'll never share your email with anyone else."
      >
        <b-form-input
          id="input-1"
          v-model="form.email"
          type="email"
          placeholder="Enter email"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-2" label="Your Name:" label-for="input-2">
        <b-form-input
          id="input-2"
          v-model="form.values.name"
          placeholder="Enter name"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-3" label="Food:" label-for="input-3">
        <b-form-select
          id="input-3"
          v-model="form.values.select"
          :options="form.selectOptions"
        ></b-form-select>
      </b-form-group>

      <b-form-group id="input-group-4">
        <b-form-checkbox-group v-model="form.values.checked" id="checkboxes-4">
          <b-form-checkbox value="me">Check me out</b-form-checkbox>
          <b-form-checkbox value="that">Check that out</b-form-checkbox>
        </b-form-checkbox-group>
      </b-form-group>

      <b-form-group label="Radios using options">
        <b-form-radio-group
          id="radio-group-1"
          v-model="form.values.radio"
          :options="form.radioOptions"
          name="radio-options"
        ></b-form-radio-group>
      </b-form-group>

      <b-form-group id="input-group-5">
        <input-date-picker v-model="form.values.datepicked" />
        <small tabindex="-1" class="form-text text-muted">Result: {{ form.values.datepicked }}</small>
      </b-form-group>

      <b-form-group id="input-group-6" label="Typeahead with static options" label-for="input-6">
        <input-typeahead
          id="input-6"
          v-model="form.values.typeaheadStatic"
          :options="form.typeaheadOptions"
        />
        <small tabindex="-1" class="form-text text-muted">Result: {{ form.values.typeaheadStatic }}</small>
      </b-form-group>

      <b-form-group id="input-group-7" label="Typeahead with dynamic options" label-for="input-7">
        <input-typeahead
          id="input-7"
          v-model="form.values.typeaheadDynamic"
          :search-func="getTypeaheadOptions"
        />
        <small tabindex="-1" class="form-text text-muted">Result: {{ form.values.typeaheadDynamic }}</small>
      </b-form-group>

      <b-form-group id="input-group-8" label="Upload file" label-for="input-8">
        <input-file
          id="input-8"
          v-model="form.values.file"
          :api="form.fileUploadApi"
          placeholder="(Tap here to add attachment)"
        />
      </b-form-group>

      <div class="d-flex justify-content-end">
        <b-button type="reset" @click="resetForm()" variant="outline-secondary" class="ml-2">Reset</b-button>
        <b-button type="submit" variant="primary" class="ml-2 px-4">Submit</b-button>
      </div>

    </div>
    <div class="styleguide__block">
      <b-button v-b-modal.modal-1>Launch demo modal</b-button>
      <b-modal
        id="modal-1"
        title="BootstrapVue"
        cancel-variant="outline-primary"
      >
        <p class="my-4">Hello from modal!</p>
      </b-modal>
    </div>
    <div class="styleguide__block">
      <b-collapse v-model="table.filters.show" id="table-filter">
        <div class="pb-3">[Filters here...]</div>
      </b-collapse>
      <div class="d-flex justify-content-between">
        <div class="d-flex align-items-baseline">
          <h6 class="mr-2">Table title</h6>
          <b-link @click="table.filters.show = !table.filters.show">Filters<fa-icon :icon="table.filters.show ? 'caret-up' : 'caret-down'" class="ml-1" /></b-link>
        </div>
        <div class="d-flex align-items-baseline">
          <b-link><fa-icon icon="download" class="mr-1" />Export</b-link>
        </div>
      </div>
      <b-table striped borderless
        :items="table.items"
        :fields="table.fields"
        :per-page="table.perPage"
        :current-page="table.currentPage"
      >
        <template slot="actions" slot-scope="data">
          <b-button :variant=" data.item.selected ? 'success' : 'outline-success'" size="sm">{{ data.item.selected ? 'Selected' : 'Select' }}<fa-icon v-if="data.item.selected" icon="check" class="ml-2" /></b-button>
        </template>
        <template slot="seemore" slot-scope="data">
          <b-button variant="primary" size="sm"><fa-icon icon="ellipsis-h" /></b-button>
          <b-link class="ml-2"><fa-icon icon="eye-slash" size="lg" /></b-link>
        </template>
      </b-table>
      <b-pagination
        v-model="table.currentPage"
        :total-rows="table.items.length"
        :per-page="table.perPage"
        aria-controls="my-table"
        align="center"
      ></b-pagination>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
const defaultValues = () => ({
  email: '',
  name: '',
  select: null,
  checked: [],
  datepicked: null,
  radio: 'first',
  typeaheadStatic: null,
  typeaheadDynamic: null,
  file: null,
})
export default {
  data() {
    return {
      form: {
        values: defaultValues(),
        fileUploadApi: '/api/upload',
        radioOptions: [
          { text: 'Toggle this custom radio', value: 'first' },
          { text: 'Or toggle this other custom radio', value: 'second' },
          { text: 'This one is Disabled', value: 'third', disabled: true },
          { text: 'This is the 4th radio', value: { fourth: 4 } },
        ],
        selectOptions: [
          { text: 'Select', value: null },
          { text: 'Option 1', value: 'option-1' },
          { text: 'Option 2', value: 'option-2' },
          { text: 'Option 3', value: 'option-3' },
          { text: 'Option 4', value: 'option-4' },
        ],
        typeaheadOptions: [
          { text: "Alberta", value: "AB" },
          { text: "British Columbia", value: "BC" },
          { text: "Manitoba", value: "MB" },
          { text: "New Brunswick", value: "NB" },
          { text: "Newfoundland and Labrador", value: "NL" },
          { text: "Nova Scotia", value: "NS" },
          { text: "Northwest Territories", value: "NT" },
          { text: "Nunavut", value: "NU" },
          { text: "Ontario", value: "ON" },
          { text: "Prince Edward Island", value: "PE" },
          { text: "Quebec", value: "QC" },
          { text: "Saskatchewan", value: "SK" },
          { text: "Yukon", value: "YT" },
        ]
      },
      table: {
        filters: {
          show: false,
        },
        perPage: 5,
        currentPage: 1,
        fields: [
          { key: 'first_name', sortable: true },
          { key: 'last_name', sortable: true },
          'age',
          { key: 'actions', label: 'Actions' },
          { key: 'seemore', label: '' }
        ],
        items: [
          { age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },
          { age: 21, first_name: 'Larsen', last_name: 'Shaw', selected: true },
          { age: 89, first_name: 'Geneva', last_name: 'Wilson' },
          { age: 38, first_name: 'Jami', last_name: 'Carney' },
          { age: 41, first_name: 'Thomas', last_name: 'Wilson' },
          { age: 18, first_name: 'Sara', last_name: 'Brown' },
          { age: 22, first_name: 'Jamila', last_name: 'Leonard' },
        ]
      },
    }
  },
  methods: {
    resetForm() { this.form.values = defaultValues() },
    async getTypeaheadOptions(query) {
      const results = await this.fakeApi()
      return results
    },
    fakeApi () {
      const APIresult = this.form.typeaheadOptions
      return new Promise(function(resolve, reject) {
        setTimeout(() => {
          resolve(APIresult);
        }, 500);
      });
    }
  }
}
</script>
<style scoped>
  .styleguide__block:after {
    content: '';
    display: block;
    margin: 1rem 3%;
    border-bottom: dashed 2px #efefef;
  }
</style>
