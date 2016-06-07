Vue = require('vue')
Vue.use(require('vue-resource'));

Vue.component 'ticket-form',
  data: ->
    agegroup: 'student'