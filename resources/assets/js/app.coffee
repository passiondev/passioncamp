module.exports = require('./register')
module.exports = require('./transaction')
module.exports = require('./components/Waiver')
module.exports = require('./components/TicketForm')

require('jquery.payment')

Vue = require('vue')
new Vue
    el: 'body'

$ ->
  $('.js-form-input-date').inputmask({ alias: "mm/dd/yyyy"});
  $('.js-form-input-card-number').payment('formatCardNumber')
  $('.js-form-input-card-cvc').payment('formatCardCVC')
  $('.js-form-input-card-expiry').payment('formatCardExpiry')
  $('[data-numeric]').payment('restrictNumeric')

  $('.ui.sidebar')
    .sidebar('attach events', '.toc.item')

  $('.js-draggable').draggable
    containment: 'document'
    cursor: 'move'
    cursorAt:
      left: 10
    helper: 'clone'
    opacity: .6
    appendTo: '.pusher'
    revert: 'invalid'
    revertDuration: 200
    addClasses: false
  $('.js-droppable').droppable
    drop: (e, ui) ->
      console.log $(@)
      $(ui.draggable).appendTo($('.tickets .segments', @));
