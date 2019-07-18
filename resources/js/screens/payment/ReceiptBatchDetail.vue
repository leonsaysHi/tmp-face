<template>
  <div>
    <div class="d-flex justify-content-between mb-3">
      <h3>Receipt Batch Detail</h3>
    </div>
    <b-row>
      <b-col cols="12" md="6" lg="3">
        <b-form-group
          label="Batch #"
          label-for="input-batch"
        >
          <b-form-input
            id="input-batch"
            v-model="form.batch"
            type="text"
            required
          ></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="6" lg="3">
        <b-form-group
          label="PO"
          label-for="input-po"
        >
          <b-form-input
            id="input-po"
            v-model="form.po"
            type="text"
            required
          ></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="6" lg="3">
        <b-form-group
          label="Status"
          label-for="input-status"
        >
          <b-form-select
            id="input-status"
            v-model="form.status"
            :options="statusOptions"
            required
          ></b-form-select>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="6" lg="3">
        <b-form-group
          label="Receipt Type"
          label-for="input-receipt-type"
        >
          <b-form-select
            id="input-receipt-type"
            v-model="form.receipt_type"
            :options="receiptTypeOptions"
            required
          ></b-form-select>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row align-h="end">
      <b-col cols="auto"><b-button type="reset" variant="outline-secondary">Reset</b-button></b-col>
      <b-col cols="auto"><b-button type="submit" variant="primary" class="px-4">Submit</b-button></b-col>
    </b-row>
    <hr class="lg secondary" />
    <b-collapse v-model="table.filters.show">
      <b-row>
        <b-col cols="12" md="4">
          <b-form-group
            label="Batch #"
            label-for="input-filter-batch"
          >
            <b-form-input
              id="input-filter-batch"
              v-model="table.filters.batch"
              type="text"
              required
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group
            label="PO"
            label-for="input-filter-po"
          >
            <b-form-input
              id="input-filter-po"
              v-model="table.filters.po"
              type="text"
              required
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group
            label="Receipt Type"
            label-for="input-filter-receipt-type"
          >
            <b-form-select
              id="input-filter-receipt-type"
              v-model="table.filters.receipt_type"
              :options="receiptTypeOptions"
              required
            ></b-form-select>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row align-h="end" class="pb-3">
        <b-col cols="auto"><b-button type="reset" variant="outline-secondary">Reset</b-button></b-col>
        <b-col cols="auto"><b-button type="submit" variant="primary" class="px-4">Filter</b-button></b-col>
      </b-row>
    </b-collapse>
    <div class="d-flex align-items-baseline justify-content-between">
      <div class="d-flex align-items-baseline">
        <h6 class="mr-2">Results</h6>
        <b-link @click="table.filters.show = !table.filters.show">Filters<fa-icon :icon="table.filters.show ? 'caret-up' : 'caret-down'" class="ml-1" /></b-link>
      </div>
      <div class="d-flex">
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
        <b-link class="mr-2">View Event Detail</b-link>
        <b-link class="mr-2">View Receipt Detail</b-link>
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
      form:{
        batch: null,
        po: null,
        status: null,
        receipt_type: null,
      },
      statusOptions: [
        { value: 1, text: 'status1' },
        { value: 2, text: 'status2' },
        { value: 3, text: 'status3' },
        { value: 4, text: 'status4' },
      ],
      receiptTypeOptions: [
        { value: 1, text: 'type1' },
        { value: 2, text: 'type2' },
        { value: 3, text: 'type3' },
      ],
      table: {
        perPage: 5,
        currentPage: 1,
        filters: {
          show: false,
          batch: null,
          po: null,
          receipt_type: null,
        },
        fields: [
          { key: 'batch', label: 'Batch #', sortable: true },
          { key: 'amount', label: 'Total Amount', sortable: true },
          { key: 'nongift_cost', label: 'Non-Gift Cost', sortable: true },
          { key: 'gift_cost', label: 'Gift Cost', sortable: true },
          { key: 'status', label: 'Status', sortable: true },
          { key: 'receive_date', label: 'Receive Date', sortable: true },
          { key: 'receipt_type', label: 'Receipt Type', sortable: true },
          { key: 'actions', label: '' },
        ],
        items: [
          { batch: '123456', amount: 300, nongift_cost: 200, gift_cost: 100, status: 'status1', receive_date: new Date(), receipt_type: 'type1' },
          { batch: '456789', amount: 400, nongift_cost: 300, gift_cost: 100, status: 'status2', receive_date: new Date(), receipt_type: 'type2' },
          { batch: '741582', amount: 500, nongift_cost: 300, gift_cost: 200, status: 'status3', receive_date: new Date(), receipt_type: 'type1' },
          { batch: '852963', amount: 600, nongift_cost: 200, gift_cost: 400, status: 'status4', receive_date: new Date(), receipt_type: 'type2' },
          { batch: '978654', amount: 700, nongift_cost: 500, gift_cost: 200, status: 'status5', receive_date: new Date(), receipt_type: 'type3' },
          { batch: '645321', amount: 800, nongift_cost: 500, gift_cost: 300, status: 'status6', receive_date: new Date(), receipt_type: 'type1' }
        ]
      },
    }
  }
}
</script>
