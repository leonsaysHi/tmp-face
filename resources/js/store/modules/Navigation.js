const items = {
  home: { label: 'Home', href:'/home'},
  speakerManagement: { label: 'Speaker Management', href:'#'},
  packageManagement: { label: 'Package Management', href:'#'},
  satelliteVisit: { label: 'Satellite Visit', href:'#'},
  satelliteAudit: { label: 'Satellite Audit', href:'#'},
  satelliteReport: { label: 'Satellite Report', href:'#'},
  help: { label: 'Help', href:'#'},
  satelliteVisitSub1: { label: 'Satellite Visit sub1', href:'#'},
  satelliteVisitSub2: { label: 'Satellite Visit sub2', href:'#'},
  satelliteVisitSub3: { label: 'Satellite Visit sub3', href:'#'},
}

export default {
  namespaced: true,
  strict: process.env.NODE_ENV !== 'production',
  state: {
    navbarLinks: [
      items.home,
      items.speakerManagement,
      items.packageManagement,
      { ...items.satelliteVisit, subMenus: [ items.satelliteVisitSub1, items.satelliteVisitSub2, items.satelliteVisitSub3 ]},
      items.satelliteAudit,
      items.satelliteReport,
      items.help,
    ]
  },
  mutations: {
  },
  getters: {
  },
  actions: {
  }
}
