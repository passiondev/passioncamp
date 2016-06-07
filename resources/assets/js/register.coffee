Vue = require('vue')
chunk = require('lodash.chunk')

Ticket = (number) ->
  this.number = number
  this.first_name = null
  this.last_name = null
  this.email = null
  this.phone = null
  this.birthdate = null
  this.gender = null
  this.shirtsize = null
  this.grade = null
  this.school = null
  this.allergies = null
  this.roomate_requested = null
  
Vue.component 'register-form',
  data: ->
    num_tickets: null
    fund_amount: null
    fund_amount_other: null
    payment_amount_type: null
    tickets: tickets_data
  watch:
      num_tickets: (num_tickets) ->
        @num_tickets = 1 if num_tickets < 1
        @num_tickets = 200 if num_tickets > 200

        while @num_tickets > @tickets.length
          @tickets.push new Ticket(@tickets.length + 1)

        while @num_tickets < @tickets.length
          @tickets.pop()
      fund_amount_other: (amount) ->
        @fund_amount = 'other' if amount > 0
      fund_amount: (amount) ->
        @fund_amount_other = null if amount >= 0

    methods:
      submitHandler: (e) ->
        if @payment_method == 'check' or (@payment_method == 'none' and @grand_total == 0)
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

    computed:
      ticket_rows: ->
        chunk(@tickets, 2)
      ticket_price: ->
        if @num_tickets >= 2 then ticket_price-20 else ticket_price
      ticket_total: ->
        @num_tickets * @ticket_price
      donation_total: ->
        if @fund_amount == 'other' then @fund_amount_other else @fund_amount
      deposit_amount: ->
        @num_tickets * 60 + @donation_total
      full_amount: ->
        @ticket_total + @donation_total
      payment_amount: ->
        if @payment_amount_type == 'deposit' then @deposit_amount else @full_amount
