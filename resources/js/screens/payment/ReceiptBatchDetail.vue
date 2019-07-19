<template>
  <div>
    <div class="styleguide__block">
      <div class="d-flex justify-content-between mb-3">
        <h3>Receipt Batch Detail</h3>
        <div class="d-flex align-items-start">
          <b-button variant="outline-primary" class="mr-3">View Event</b-button>
          <div>
            <div>Receipt Status</div>
            <div class="h4">Not Received</div>
          </div>
        </div>
      </div>
    </div>
    <h6>Receipt Batch Detail</h6>
    <b-row class="mb-3">
      <b-col>
        <small>Batch #:</small>
        <div class="lead">20181102</div>
      </b-col>
      <b-col>
        <small>Status:</small>
        <div class="lead">Not Received</div>
      </b-col>
      <b-col>
        <small>Event Total Expense:</small>
        <div class="lead">CNY 780.0</div>
      </b-col>
      <b-col>
        <small>Event Total Amount:</small>
        <div class="lead">CNY 780.0</div>
      </b-col>
      <div class="w-100 my-2"></div>
      <b-col>
        <small>Non-Gift Expense (Tax Excluded):</small>
        <div class="lead">CNY 380.0</div>
      </b-col>
      <b-col>
        <small>Non-Gift Receipt Total:</small>
        <div class="lead">CNY 380.0</div>
      </b-col>
      <b-col>
        <small>Gift Expense (Tax Included):</small>
        <div class="lead">CNY 400.0</div>
      </b-col>
      <b-col>
        <small>Gift Receipt Total:</small>
        <div class="lead">CNY 500.0</div>
      </b-col>
    </b-row>
    <b-row align-h="end">
      <b-col cols="auto"><b-button type="submit" variant="primary" class="px-4">Mark Receipt as Received</b-button></b-col>
    </b-row>
    <hr class="lg secondary" />
    <b-collapse v-model="table.filters.show">
      <b-row>
        <b-col cols="12" md="4">
          <b-form-group
            label="Receipt #"
            label-for="input-filter-batch"
          >
            <b-form-input
              id="input-filter-batch"
              v-model="table.filters.receipt"
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
    </div>
    <b-table striped borderless
      :items="table.items"
      :fields="table.fields"
      :per-page="table.perPage"
      :current-page="table.currentPage"
    >
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
          receipt: null,
          po: null,
          receipt_type: null,
        },
        fields: [
          { key: 'receipt', label: 'Receipt #', sortable: true },
          { key: 'po', label: 'PO', sortable: true },
          { key: 'receipt_type', label: 'Receipt Type', sortable: true },
          { key: 'receipt_total_amount', label: 'Receipt Total Amount', sortable: true },
        ],
        items: [
          { receipt: '123456', po: '3456', receipt_total_amount: 300, receipt_type: 'type1' },
          { receipt: '456789', po: '6789', receipt_total_amount: 400, receipt_type: 'type2' },
          { receipt: '741582', po: '1582', receipt_total_amount: 500, receipt_type: 'type1' },
          { receipt: '852963', po: '2963', receipt_total_amount: 600, receipt_type: 'type2' },
          { receipt: '978654', po: '8654', receipt_total_amount: 700, receipt_type: 'type3' },
          { receipt: '645321', po: '5321', receipt_total_amount: 800, receipt_type: 'type1' },
        ]
      },
    }
  }
}
</script>
