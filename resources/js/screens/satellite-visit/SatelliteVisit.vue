<template>
  <div>
    <satellite-visit-list />
    <satellite-visit-event-view />
  </div>
</template>

<script>
import SatelliteVisitList from './SatelliteVisitList'
import SatelliteVisitEventView from './SatelliteVisitEventView'
export default {
  components: {
    SatelliteVisitList,
    SatelliteVisitEventView,
  },
  data() {
    return {}
  }
}
</script>
