<template>
  <div>
    <div class="d-flex justify-content-between mb-3">
      <h3>Search Event</h3>
    </div>
    <b-row>
      <b-col>
        <b-form-group>
          <b-form-input
            id="input-search"
            v-model="form.values.search"
            type="text"
            placeholder="Event Name"
          ></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="auto"><b-button type="submit" variant="primary" class="px-4">Search</b-button></b-col>
    </b-row>
    <b-link @click="form.advanced.show = !form.advanced.show">Advanced<fa-icon :icon="form.advanced.show ? 'caret-up' : 'caret-down'" class="ml-1" /></b-link>
    <b-collapse v-model="form.advanced.show" id="table-filter">
      <b-row class="pt-3">
        <b-col cols="12" md="4">
          <b-form-group
            label="Proposal #"
            label-for="input-advanced-proposal"
          >
            <b-form-input
              id="input-advanced-proposal"
              v-model="form.advanced.values.proposal"
              type="text"
              placeholder="Search Event"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <div role="group" class="form-group">
            <label for="input-advanced-start-date" class="d-block">Event Date</label>
            <div class="d-flex">
              <input-date-picker
                v-model="form.advanced.values.start_date"
                placeholder="Start Date"
                class="flex-grow-1"
              />
              <input-date-picker
                v-model="form.advanced.values.end_date"
                placeholder="End Date"
                class="flex-grow-1 ml-1"
              />
            </div>
          </div>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group
            label="City"
            label-for="input-advanced-city"
          >
            <b-form-input
              id="input-advanced-city"
              v-model="form.advanced.values.city"
              type="text"
              placeholder="City"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group
            label="Budget"
            label-for="input-advanced-budget"
          >
            <b-form-input
              id="input-advanced-budget"
              v-model="form.advanced.values.budget"
              type="text"
              placeholder="Budget"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group id="input-group-createdby" label="Created by" label-for="input-advanced-createdby">
            <input-typeahead
              id="input-advanced-createdby"
              v-model="form.advanced.values.createdby"
              :options="form.advanced.createdbyOptions"
              placeholder="Created By"
            />
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <div role="group" class="form-group">
            <label for="input-advanced-createdon" class="d-block">Created on</label>
            <input-date-picker
              v-model="form.advanced.values.createdon"
              placeholder="Created On"
            />
          </div>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group id="input-group-status" label="Status" label-for="input-advanced-status">
            <b-form-select
              id="input-advanced-status"
              v-model="form.advanced.values.status"
              :options="form.advanced.statusOptions"
            ></b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group id="input-group-visitor" label="Visitor Name" label-for="input-advanced-visitor">
            <input-typeahead
              id="input-advanced-visitor"
              v-model="form.advanced.values.visitor"
              :options="form.advanced.visitorOptions"
              placeholder="Visitor Name"
            />
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group id="input-group-visitor-status" label="Visitor Status" label-for="input-advanced-visitor-status">
            <b-form-select
              id="input-advanced-visitor-status"
              v-model="form.advanced.values.visitorStatus"
              :options="form.advanced.visitorStatusOptions"
            ></b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group id="input-group-event-type" label="Event Type" label-for="input-advanced-event-type">
            <b-form-select
              id="input-advanced-event-type"
              v-model="form.advanced.values.eventType"
              :options="form.advanced.eventTypeOptions"
            ></b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group id="input-group-event-detail-type" label="Event Detail Type" label-for="input-advanced-event-detail-type">
            <input-typeahead
              id="input-advanced-event-detail-type"
              v-model="form.advanced.values.eventDetailType"
              :options="form.advanced.eventDetailTypeOptions"
              placeholder="Event Detail Type"
            />
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group id="input-group-cloud-event" label="Cloud Event" label-for="input-advanced-cloud-event">
            <b-form-select
              id="input-advanced-cloud-event"
              v-model="form.advanced.values.cloudEvent"
              :options="form.advanced.cloudEventOptions"
            ></b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group id="input-group-bu" label="BU Code" label-for="input-advanced-bu">
            <b-form-select
              id="input-advanced-bu"
              v-model="form.advanced.values.bu"
              :options="form.advanced.buOptions"
            ></b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group
            label="Requestor Employee #"
            label-for="input-advanced-employee"
          >
            <b-form-input
              id="input-advanced-employee"
              v-model="form.advanced.values.employee"
              type="text"
              placeholder="Employee #"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4" class="d-flex align-items-end">
          <div class="d-flex mb-3">
            <b-button type="reset" variant="outline-secondary" class="flex-grow-1">Reset</b-button>
            <b-button type="submit" variant="primary" class="flex-grow-1 ml-1">Submit</b-button>
          </div>
        </b-col>
      </b-row>
    </b-collapse>
    <hr class="lg secondary" />
    <div class="d-flex justify-content-between">
      <div class="d-flex align-items-baseline">
        <h6 class="mr-2">Results</h6>
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
        <b-button variant="primary" size="sm"><fa-icon icon="ellipsis-h" /></b-button>
      </template>
    </b-table>
    <b-pagination  v-if="table.items.length > table.currentPage"
      v-model="table.currentPage"
      :total-rows="table.items.length"
      :per-page="table.perPage"
      aria-controls="my-table"
      align="center"
    ></b-pagination>
  </div>
</template>

<script>

export default {
  data() {
    return {
      form: {
        values: {
          search: null
        },
        advanced: {
          values: {
            batch: null,
            start_date: null,
            end_date: null,
            city: null,
            createdby: null,
            createdon: null,
            status: null,
            visitor: null,
            visitorStatus: null,
            eventType: null,
            eventDetailType: null,
            cloudEvent: null,
            bu: null,
            employee: null,
          },
          createdbyOptions: [
            { text: "User1", value: "user-1" },
            { text: "User2", value: "user-2" },
            { text: "User3", value: "user-3" },
          ],
          visitorOptions: [
            { text: "Visitor 1", value: "visitor-1" },
            { text: "Visitor 2", value: "visitor-2" },
            { text: "Visitor 3", value: "visitor-3" },
          ],
          visitorStatusOptions: [
            { text: "Status 1", value: "status-1" },
            { text: "Status 2", value: "status-2" },
            { text: "Status 3", value: "status-3" },
          ],
          statusOptions: [
            { text: "Status 1", value: "status-1" },
            { text: "Status 2", value: "status-2" },
            { text: "Status 3", value: "status-3" },
          ],
          eventTypeOptions: [
            { text: "Type 1", value: "type-1" },
            { text: "Type 2", value: "type-2" },
            { text: "Type 3", value: "type-3" },
          ],
          eventDetailTypeOptions: [
            { text: "Detail Type 1", value: "detail-type-1" },
            { text: "Detail Type 2", value: "detail-type-2" },
            { text: "Detail Type 3", value: "detail-type-3" },
          ],
          cloudEventOptions: [
            { text: "Cloud Event 1", value: "cloud-event-1" },
            { text: "Cloud Event 2", value: "cloud-event-2" },
            { text: "Cloud Event 3", value: "cloud-event-3" },
          ],
          buOptions: [
            { text: "BU 1", value: "bu-1" },
            { text: "BU 2", value: "bu-2" },
            { text: "BU 3", value: "bu-3" },
          ],
          show: false,
        },
      },
      table: {
        perPage: 5,
        currentPage: 1,
        fields: [
          { key: 'proposal', label: 'Proposal #', sortable: true },
          { key: 'name', label: 'Event Name', sortable: true },
          { key: 'type', label: 'Event Type', sortable: true },
          { key: 'date', label: 'Event Date', sortable: true },
          { key: 'budget', label: 'Budget', sortable: true },
          { key: 'status', label: 'Status', sortable: true },
          { key: 'organization', label: 'Organization', sortable: true },
          { key: 'submitter', label: 'Submitter', sortable: true },
          { key: 'actions', label: '' },
        ],
        items: [
          { proposal: '123456', name: 'Meeting Name', type: 'type1', date: new Date(), budget: 300, status: 'status1', organization: 'Pfizer', submitter: 'Name' },
          { proposal: '456789', name: 'Meeting Name', type: 'type2', date: new Date(), budget: 200, status: 'status3', organization: 'Pfizer', submitter: 'Name' },
          { proposal: '741582', name: 'Meeting Name', type: 'type3', date: new Date(), budget: 450, status: 'status2', organization: 'Pfizer', submitter: 'Name' },
          { proposal: '852963', name: 'Meeting Name', type: 'type3', date: new Date(), budget: 500, status: 'status1', organization: 'Pfizer', submitter: 'Name' },
          { proposal: '978654', name: 'Meeting Name', type: 'type2', date: new Date(), budget: 100, status: 'status1', organization: 'Pfizer', submitter: 'Name' },
          { proposal: '645321', name: 'Meeting Name', type: 'type3', date: new Date(), budget: 880, status: 'status2', organization: 'Pfizer', submitter: 'Name' }
        ]
      },
    }
  }
}
</script>
