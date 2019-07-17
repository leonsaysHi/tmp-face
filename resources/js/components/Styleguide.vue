<template>
  <div class="styleguide">
    <div class="my-3">
      <h1>h1. Bootstrap heading</h1>
      <h2>h2. Bootstrap heading</h2>
      <h3>h3. Bootstrap heading</h3>
      <h4>h4. Bootstrap heading</h4>
      <h5>h5. Bootstrap heading</h5>
      <h6>h6. Bootstrap heading</h6>
    </div>
    <div class="my-3">
      <b-button variant="primary">Primary</b-button>
      <b-button variant="outline-primary">Outline primary</b-button>
      <b-button variant="secondary">Secondary</b-button>
      <b-button variant="outline-secondary">Outline secondary</b-button>
      <b-button variant="success">Success</b-button>
      <b-button variant="info">info</b-button>
      <b-button variant="danger">Danger</b-button>
      <b-link>Link</b-link>
    </div>
    <div>
      <hr />
      <hr class="primary" />
      <hr class="secondary" />
      <hr class="lg" />
      <hr class="primary lg" />
      <hr class="secondary lg" />
    </div>
    <div class="my-3">
      <fa-icon icon="calendar-alt" />
      <fa-icon icon="check" />
      <fa-icon icon="plus-square" size="lg" />
      <fa-icon icon="download" size="2x" />
      <fa-icon icon="user-alt" size="3x" />
      <fa-icon icon="ellipsis-h" size="lg" />
    </div>
    <div class="my-3">
      <div class="d-flex justify-content-between mb-3">
        <h3>Screen title</h3>
        <div>
          <div>Extra infos</div>
          <div class="h4">Extra status</div>
        </div>
      </div>
    </div>
    <div class="my-3">
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
          <div class="lead">- -</div>
        </b-col>
        <b-col>
          <small>Title:</small>
          <div class="lead">Senior Doctor</div>
        </b-col>
        <b-col>
          <small>Specialization:</small>
          <div class="lead">Psycology</div>
        </b-col>
      </b-row>
    </div>
    <div class="my-3">
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
          required
          placeholder="Enter email"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-2" label="Your Name:" label-for="input-2">
        <b-form-input
          id="input-2"
          v-model="form.name"
          required
          placeholder="Enter name"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-3" label="Food:" label-for="input-3">
        <b-form-select
          id="input-3"
          v-model="form.food"
          :options="foods"
          required
        ></b-form-select>
      </b-form-group>

      <b-form-group id="input-group-4">
        <b-form-checkbox-group v-model="form.checked" id="checkboxes-4">
          <b-form-checkbox value="me">Check me out</b-form-checkbox>
          <b-form-checkbox value="that">Check that out</b-form-checkbox>
        </b-form-checkbox-group>
      </b-form-group>

      <b-form-group label="Radios using options">
        <b-form-radio-group
          id="radio-group-1"
          v-model="form.radios.selected"
          :options="form.radios.options"
          name="radio-options"
        ></b-form-radio-group>
      </b-form-group>

      <b-form-group id="input-group-5">
        <input-date-picker />
      </b-form-group>

      <b-form-group id="input-group-6" label="Typeahead with static options" label-for="input-6">
        <input-typeahead
          id="input-6"
          v-model="form.typeahead.value1"
          :options="form.typeahead.options"
        />
        <small tabindex="-1" class="form-text text-muted">Result: {{ form.typeahead.value1 }}</small>
      </b-form-group>

      <b-form-group id="input-group-7" label="Typeahead with dynamic options" label-for="input-7">
        <input-typeahead
          id="input-7"
          v-model="form.typeahead.value2"
          :search-func="getTypeaheadOptions"
        />
        <small tabindex="-1" class="form-text text-muted">Result: {{ form.typeahead.value2 }}</small>
      </b-form-group>

      <b-button type="submit" variant="primary">Submit</b-button>
    </div>
    <div class="my-3">
      <b-button v-b-modal.modal-1>Launch demo modal</b-button>
      <b-modal
        id="modal-1"
        title="BootstrapVue"
        cancel-variant="outline-primary"
      >
        <p class="my-4">Hello from modal!</p>
      </b-modal>
    </div>
    <div class="my-3">
      <div class="d-flex align-items-baseline justify-content-between">
        <h6>Table title</h6>
        <div class="d-flex">
          <b-link><fa-icon icon="download" class="mr-1" />Export</b-link>
        </div>
      </div>
      <b-table striped borderless
        :items="table.items"
        :fields="table.fields"
      >
        <template slot="actions" slot-scope="data">
          <b-button :variant=" data.item.selected ? 'success' : 'outline-success'" size="sm">{{ data.item.selected ? 'Selected' : 'Select' }}&nbsp;<fa-icon v-if="data.item.selected" icon="check" /></b-button>
        </template>
        <template slot="seemore" slot-scope="data">
          <b-button variant="primary" size="sm"><fa-icon icon="ellipsis-h" /></b-button>
        </template>
      </b-table>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  data() {
    return {
      form: {
        email: '',
        name: '',
        food: null,
        checked: [],
        radios: {
          selected: 'first',
          options: [
            { text: 'Toggle this custom radio', value: 'first' },
            { text: 'Or toggle this other custom radio', value: 'second' },
            { text: 'This one is Disabled', value: 'third', disabled: true },
            { text: 'This is the 4th radio', value: { fourth: 4 } }
          ]
        },
        typeahead: {
          value1: null,
          value2: null,
          options: [
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
            { text: "Yukon", value: "YT" }
          ],
        }
      },
      foods: [{ text: 'Select One', value: null }, 'Carrots', 'Beans', 'Tomatoes', 'Corn'],
      table: {
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
          { age: 38, first_name: 'Jami', last_name: 'Carney' }
        ]
      },
    }
  },
  methods: {
    async getTypeaheadOptions(query) {
      const results = await this.fakeApi()
      return results
    },
    fakeApi () {
      const APIresult = this.form.typeahead.options
      return new Promise(function(resolve, reject) {
        setTimeout(() => {
          resolve(APIresult);
        }, 500);
      });
    }
  }
}
</script>
