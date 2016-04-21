Vue = require('vue')

data =
  payment_method: null

methods =
  submitHandler: (e) ->
    if @payment_method != 'credit'
      return e.target.submit()

    @form = $(e.target)

    cardNumber = $('.js-form-input-card-number').val()
    cardType = $.payment.cardType cardNumber
    cardExpiry = $('.js-form-input-card-expiry').payment('cardExpiryVal')
    cardCVC = $('.js-form-input-card-cvc').val()

    if ! $.payment.validateCardNumber cardNumber
      @form.find('.payment-errors').text 'Your card number is not valid.'
      return false

    if ! $.payment.validateCardExpiry cardExpiry
      @form.find('.payment-errors').text 'Your card expiration date is not valid.'
      return false

    if ! $.payment.validateCardCVC(cardCVC, cardType)
      @form.find('.payment-errors').text 'Your card security code date is not valid.'
      return false

    @buttons = $('button', @form).prop('disabled', true)
    Stripe.card.createToken(@form, @stripeResponseHandler)
  stripeResponseHandler: (status, response) ->
    if (response.error)
      # Show the errors on the form
      @form.find('.payment-errors').text response.error.message
      @buttons.prop('disabled', false)
    else
      # Insert the token into the form so it gets submitted to the server
      @form.append $('<input type="hidden" name="stripeToken" />').val response.id
      # and submit
      @form.submit()

Vue.component 'Transaction',
  data: ->
    data
  methods: methods
  props: [
    'canMakeStripePayments'
  ]
  created: ->
    @payment_method = if @canMakeStripePayments == '1' then 'credit' else 'check'
