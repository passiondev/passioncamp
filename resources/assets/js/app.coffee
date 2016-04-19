module.exports = require('./register')
module.exports = require('./transaction')

require('jquery.payment')

$ ->
  $('.js-form-input-date').inputmask({ alias: "mm/dd/yyyy"});
  $('.js-form-input-card-number').payment('formatCardNumber')
  $('.js-form-input-card-cvc').payment('formatCardCVC')
  $('.js-form-input-card-expiry').payment('formatCardExpiry')
  $('[data-numeric]').payment('restrictNumeric')
