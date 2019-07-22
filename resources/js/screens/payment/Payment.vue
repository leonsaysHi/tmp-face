<template>
  <div>
    <receipt-batch-list />
    <receipt-batch-detail />
  </div>
</template>

<script>
import ReceiptBatchList from './ReceiptBatchList'
import ReceiptBatchDetail from './ReceiptBatchDetail'
export default {
  components: {
    ReceiptBatchList,
    ReceiptBatchDetail,
  },
  data() {
    return {}
  }
}
</script>
