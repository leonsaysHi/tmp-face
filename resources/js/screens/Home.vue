<template>
  <div>
    <h3>Home</h3>
    <b-list-group>
      <b-list-group-item disabled>Home</b-list-group-item>
      <b-list-group-item><b-link @click="navigate('payment')">Payment</b-link></b-list-group-item>
      <b-list-group-item><b-link @click="navigate('styleguide')">Styleguide</b-link></b-list-group-item>
    </b-list-group>
  </div>
</template>

<script>
import { mapMutations } from "vuex";
export default {
  data() {
    return {
    }
  },
  methods: {
    ...mapMutations('Navigation', [ 'navigate' ])
  }
}
</script>
