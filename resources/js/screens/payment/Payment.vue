<template>
  <div>
    <receipt-batch-list />
    <batch-detail-receipt-view />
    <batch-detail-event-view />
  </div>
</template>

<script>
import ReceiptBatchList from './ReceiptBatchList'
import BatchDetailReceiptView from './BatchDetailReceiptView'
import BatchDetailEventView from './BatchDetailEventView'
export default {
  components: {
    ReceiptBatchList,
    BatchDetailReceiptView,
    BatchDetailEventView,
  },
  data() {
    return {}
  }
}
</script>
