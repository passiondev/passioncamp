Vue = require('vue')
Vue.use(require('vue-resource'));

Vue.component 'Waiver',
  data: ->
  methods:
    send: (e) ->
      $link = $(e.target)
      $parent = $link.parent().empty().append '<i>sending...</i>'
      @$http.get
        url: $link.attr 'href'
      .then((response) ->
          $parent.empty().append '<i>sent!</i>'
      , (response) ->
          console.log response.data
          $parent.empty().append "<i>#{response.data.status}</i>"
          $link.remove()
      )
