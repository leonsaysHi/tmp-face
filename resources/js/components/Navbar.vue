<template>
  <div class="navbar">
    <b-container>
      <b-nav>
        <template v-for="(item, idx) in items">
          <b-nav-item v-if="!item.subMenus" :key="idx"
            @click="navigate(item.screen)"
          >{{ item.label }}</b-nav-item>
          <b-nav-item-dropdown v-else :key="idx"
            :id="'navbar-sub-'+idx"
            :text="item.label"
            toggle-class="nav-link-custom"
            right
          >
            <b-dropdown-item v-for="(subitem, subidx) in item.subMenus" :key="subidx">{{ subitem.label }}</b-dropdown-item>
          </b-nav-item-dropdown>
        </template>
      </b-nav>
    </b-container>
  </div>
</template>

<script>
import { mapState, mapMutations } from "vuex";

export default {
  computed: {
    ...mapState({
      items: state => state.Navigation.navbarLinks,
    }),
  },
  methods: {
    ...mapMutations('Navigation', [ 'navigate' ])
  }
}
</script>
