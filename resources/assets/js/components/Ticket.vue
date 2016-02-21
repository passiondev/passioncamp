<template>
    <div class="col-md-6 ticket">
        <h4>Ticket @{{ num }}</h4>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][first_name]">First Name</label>
            <input class="form-control" type="text" id="tickets_@{{ num }}__first_name" name="tickets[@{{ num }}][first_name]" v-model="ticket.first_name">
        </div>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][last_name]">Last Name</label>
            <input class="form-control" type="text" id="tickets_@{{ num }}__last_name" name="tickets[@{{ num }}][last_name]" v-model="ticket.last_name">
        </div>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][email]">Email Address</label>
            <input class="form-control" type="email" id="tickets_@{{ num }}__email" name="tickets[@{{ num }}][email]" v-model="ticket.email">
        </div>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][phone]">Phone Number</label>
            <input class="form-control" type="text" id="tickets_@{{ num }}__phone" name="tickets[@{{ num }}][phone]" v-model="ticket.phone">
        </div>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][gender]">Gender</label>
            <div class="form-controls">
                <label class="radio-inline">
                    <input type="radio" value="M" name="tickets[@{{ num }}][gender]" v-model="ticket.gender" id="tickets_@{{ num }}__gender"> Male
                </label>
                <label class="radio-inline">
                    <input type="radio" value="F" name="tickets[@{{ num }}][gender]" v-model="ticket.gender" id="tickets_@{{ num }}__gender"> Female
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][grade]">Grade</label>
            <select class="form-control" id="tickets_@{{ num }}__grade" name="tickets[@{{ num }}][grade]" v-model="ticket.grade"><option value="0"></option><option value="6">6th</option><option value="7">7th</option><option value="8">8th</option><option value="9">9th</option><option value="10">10th</option><option value="11">11th</option><option value="12">12th</option></select>
        </div>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][allergies]">Allergies</label>
            <input class="form-control" type="text" id="tickets_@{{ num }}__allergies" name="tickets[@{{ num }}][allergies]" v-model="ticket.allergies">
        </div>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][birthdate]">Birthdate</label>
            <input class="form-control" placeholder="mm/dd/yyyy" type="text" id="tickets_@{{ num }}__birthdate" name="tickets[@{{ num }}][birthdate]" v-model="ticket.birthdate">
        </div>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][school]">School</label>
            <input class="form-control" type="text" id="tickets_@{{ num }}__school" name="tickets[@{{ num }}][school]" v-model="ticket.school">
        </div>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][shirtsize]">Shirt Size</label>
            <select class="form-control" id="tickets_@{{ num }}__shirtsize" name="tickets[@{{ num }}][shirtsize]" v-model="ticket.shirtsize"><option value="0"></option><option value="1">XS</option><option value="2">S</option><option value="3">M</option><option value="4">L</option><option value="5">XL</option></select>
        </div>
        <div class="form-group">
            <label class="control-label" for="tickets[@{{ num }}][roomate_requested]">Roomate Requested</label>
            <input class="form-control" type="text" name="tickets[@{{ num }}][roomate_requested]" v-model="ticket.roomate_requested" id="tickets_@{{ num }}__roomate_requested">
        </div>
    </div>
</template>

<script lang="coffee">
module.exports =
  props:
    num: Number
    ticket:
      type: Object
      coerce: (val) -> unless val? then {} else val
</script>