module.exports = require('./register')
module.exports = require('./transaction')
module.exports = require('./components/Waiver')
module.exports = require('./components/TicketForm')

require('jquery.payment')

Vue = require('vue')
new Vue
    el: 'body'

$ ->
  $.ajaxSetup
    headers:
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")

  $('.js-form-input-date').inputmask({ alias: "mm/dd/yyyy"});
  $('.js-form-input-card-number').payment('formatCardNumber')
  $('.js-form-input-card-cvc').payment('formatCardCVC')
  $('.js-form-input-card-expiry').payment('formatCardExpiry')
  $('[data-numeric]').payment('restrictNumeric')

  $('.ui.sidebar')
    .sidebar('attach events', '.toc.item')

window.App = {}
class App.Assignments
  constructor: ->
    @makeDraggable $('.js-draggable')
    @makeDroppable $('.js-droppable')
    $('.pusher').css
      height: '100%'
  makeDraggable: (el) ->
    $(el).draggable
      containment: 'document'
      cursor: 'move'
      cursorAt:
        left: 10
      helper: 'clone'
      opacity: .8
      appendTo: '.pusher'
      revert: 'invalid'
      revertDuration: 200
      addClasses: false
  makeDroppable: (el) ->
    $(el).droppable
      drop: @drop
  drop: (e, ui) ->
    $('.tickets .segments', $(e.target)).addClass('hastickets').append $(ui.draggable)
    new App.Assignment($(ui.draggable), $(e.target))

class App.Assignment
  constructor: (@ticket, @room) ->
    @ticket_id = $(@ticket).data('id')
    @previous_room_id = $(@ticket).data('room-id')
    @room_id = $(@room).data('id')

    @assign() if @room_id > 0
    @unassign() if @room_id == 0 && @previous_room_id != 0

  assign: ->
    $.ajax(
      url: "/roominglist/#{@ticket_id}/assign/#{@room_id}"
      method: "PUT"
    ).success( (data) =>
      if data.view
        $(@room).parent().empty().append($(data.view).contents())
      new App.Assignments()
    ).fail( (data) =>
      $(@ticket).prependTo $('#unassigned') if @previous_room_id == 0
      console.log? data.responseJSON.message
      
      if data.responseJSON && data.responseJSON.view
        $(@room).parent().empty().append($(data.responseJSON.view).contents())

      new App.Assignments()
    ).always( =>
      @reload(@previous_room_id) if @previous_room_id > 0
    )
  unassign: ->
    $(@ticket).prependTo $('#unassigned')
    $.ajax(
      url: "/roominglist/#{@ticket_id}/unassign"
      method: "PUT"
    ).success( (data) =>
      @reload(@previous_room_id)
      new App.Assignments()
    )
  reload: (room_id) ->
    $.ajax(
      url: "/roominglist/#{room_id}"
    ).success( (data) =>
      if data.view
        $(".room[data-id='#{room_id}").parent().empty().append($(data.view).contents())
      new App.Assignments()
    )
