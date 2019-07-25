<template>
  <div>
    <div class="d-flex justify-content-between mb-3">
      <h3>Search Event</h3>
      <div class="d-flex align-items-start">
        <satellite-new-visit-modal v-slot:default="slotProps">
          <b-button variant="outline-primary" class="mr-3" @click="slotProps.toggle()">Schedule Visit</b-button>
        </satellite-new-visit-modal>
      </div>
    </div>
    <b-row>
      <b-col>
        <small>Event ID:</small>
        <div class="lead">FDGD2345678</div>
      </b-col>
      <b-col>
        <small>WBS Code:</small>
        <div class="lead">12345678909</div>
      </b-col>
      <b-col>
        <small>Budget:</small>
        <div class="lead">$300.000</div>
      </b-col>
      <b-col>
        <small>Total Expense:</small>
        <div class="lead">$300.000</div>
      </b-col>
      <div class="w-100">
        <hr class="primary" />
      </div>
      <b-col>
        <small>Event Date:</small>
        <div class="lead">{{ dateFormat() }}</div>
      </b-col>
      <b-col>
        <small>Event Type:</small>
        <div class="lead">Conference Meeting</div>
      </b-col>
      <b-col>
        <small>City:</small>
        <div class="lead">Tokyo</div>
      </b-col>
      <b-col>
        <small>Location:</small>
        <div class="lead">Convention Center</div>
      </b-col>
      <div class="w-100 my-2"></div>
      <b-col>
        <small>Internal Atteendees:</small>
        <div class="lead">20</div>
      </b-col>
      <b-col>
        <small>Internal Atteendees:</small>
        <div class="lead">200</div>
      </b-col>
      <b-col>
        <small>Hospital:</small>
        <div class="lead">Hospital Name</div>
      </b-col>
      <b-col>
        <small>Department:</small>
        <div class="lead">Oncology</div>
      </b-col>
      <div class="w-100">
        <hr class="primary" />
      </div>
      <b-col>
        <small>Requested By:</small>
        <div class="lead">Chiemeka Yobachukwu</div>
      </b-col>
      <b-col>
        <small>Requestor Employee Number:</small>
        <div class="lead">PFZ345678978</div>
      </b-col>
      <b-col>
        <small>Requestor Phone Number:</small>
        <div class="lead">1+ (900) 123-4567</div>
      </b-col>
      <b-col>
        <small>Requestor Region:</small>
        <div class="lead">APEC</div>
      </b-col>
      <div class="w-100">
        <hr class="primary" />
      </div>
      <b-col>
        <small>Comment:</small>
        <div class="lead">Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
      </b-col>
    </b-row>
    <hr class="primary" />
    <b-row>
      <b-col>
        <h6 class="mr-2">Approver List</h6>
        <b-card header="Name">
          <b-card-text>No Approvers Added</b-card-text>
        </b-card>
      </b-col>
      <b-col>
        <h6 class="mr-2">Risk Alert</h6>
        <b-card header="Alert">
          <b-card-text>No Alerts</b-card-text>
        </b-card>
      </b-col>
    </b-row>
    <hr class="primary" />
    <h6 class="mr-2">Visit History</h6>
    <b-table striped borderless
      :items="table.items"
      :fields="table.fields"
      :per-page="table.perPage"
      :current-page="table.currentPage"
    >
      <template slot="actions" slot-scope="data">
        <b-button variant="primary" size="sm"><fa-icon icon="ellipsis-h" /></b-button>
      </template>
      <template slot="date" slot-scope="data">
        {{ dateFormat(data.value) }}
      </template>
      <template slot="creationDate" slot-scope="data">
        {{ dateFormat(data.value) }}
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
import SatelliteNewVisitModal from './SatelliteNewVisitModal'
import dateFormat from '../../mixins/dateFormat'
export default {
  mixins: [dateFormat],
  components: {
    SatelliteNewVisitModal,
  },
  data() {
    return {
      test: null,
      table: {
        perPage: 5,
        currentPage: 1,
        fields: [
          { key: 'proposal', label: 'Proposal #', sortable: true },
          { key: 'name', label: 'Event Name', sortable: true },
          { key: 'type', label: 'Event Type', sortable: true },
          { key: 'date', label: 'Event Date', sortable: true },
          { key: 'budget', label: 'Budget', sortable: true },
          { key: 'author', label: 'Created By', sortable: true },
          { key: 'creationDate', label: 'Created On', sortable: true },
          { key: 'visitor', label: 'Visitor', sortable: true },
          { key: 'actions', label: '' },
        ],
        items: [
          { proposal: '123456', name: 'Meeting Name', type: 'type1', date: new Date(), budget: 300, author: 'author1', creationDate: new Date(), visitor: 'Ellen Schneider' },
          { proposal: '456789', name: 'Meeting Name', type: 'type2', date: new Date(), budget: 200, author: 'author3', creationDate: new Date(), visitor: 'Ellen Schneider' },
          { proposal: '741582', name: 'Meeting Name', type: 'type3', date: new Date(), budget: 450, author: 'author2', creationDate: new Date(), visitor: 'Ellen Schneider' },
          { proposal: '852963', name: 'Meeting Name', type: 'type3', date: new Date(), budget: 500, author: 'author1', creationDate: new Date(), visitor: 'Ellen Schneider' },
          { proposal: '978654', name: 'Meeting Name', type: 'type2', date: new Date(), budget: 100, author: 'author1', creationDate: new Date(), visitor: 'Ellen Schneider' },
          { proposal: '645321', name: 'Meeting Name', type: 'type3', date: new Date(), budget: 880, author: 'author2', creationDate: new Date(), visitor: 'Ellen Schneider' }
        ]
      },
    }
  },
}
</script>
