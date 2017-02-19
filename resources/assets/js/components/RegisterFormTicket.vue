<template>
    <div class="card">
        <header class="card-header">
            <h4>Student #{{ ticket.number }}</h4>
        </header>
        <div class="card-block ticket">
            <div class="row">
                <div class="form-group col-lg-6">
                    <label class="control-label" :for="`tickets_${ticket.number}__first_name`">First Name</label>
                    <input class="form-control" type="text" :id="`tickets_${ticket.number}__first_name`" :name="`tickets[${ticket.number}][first_name]`" v-model="ticket.first_name">
                </div>
                <div class="form-group col-lg-6">
                    <label class="control-label" :for="`tickets_${ticket.number}__last_name`">Last Name</label>
                    <input class="form-control" type="text" :id="`tickets_${ticket.number}__last_name`" :name="`tickets[${ticket.number}][last_name]`" v-model="ticket.last_name">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label class="control-label" :for="`tickets_${ticket.number}__email`">Email Address</label>
                    <input class="form-control" type="email" :id="`tickets_${ticket.number}__email`" :name="`tickets[${ticket.number}][email]`" v-model="ticket.email">
                </div>
                <div class="form-group col-lg-6">
                    <label class="control-label" :for="`tickets_${ticket.number}__phone`">Phone Number</label>
                    <input class="form-control" type="text" :id="`tickets_${ticket.number}__phone`" :name="`tickets[${ticket.number}][phone]`" v-model="ticket.phone">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4">
                    <label class="control-label" :for="`tickets_${ticket.number}__birthdate`">Birthdate</label>
                    <input class="form-control js-form-input-date" placeholder="mm/dd/yyyy" type="text" :id="`tickets_${ticket.number}__birthdate`" :name="`tickets[${ticket.number}][birthdate]`" v-model="ticket.birthdate">
                </div>
                <div class="form-group col-lg-4">
                    <label class="control-label" :for="`tickets_${ticket.number}__gender`">Gender</label>
                    <div class="form-controls form-controls--radio">
                        <label class="radio-inline">
                            <input type="radio" value="M" :name="`tickets[${ticket.number}][gender]`" v-model="ticket.gender" :id="`tickets_${ticket.number}__gender`"> Male
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="F" :name="`tickets[${ticket.number}][gender]`" v-model="ticket.gender" :id="`tickets_${ticket.number}__gender`"> Female
                        </label>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <label class="control-label" :for="`tickets_${ticket.number}__shirtsize`">Shirt Size</label>
                    <select class="form-control" :id="`tickets_${ticket.number}__shirtsize`" :name="`tickets[${ticket.number}][shirtsize]`" v-model="ticket.shirtsize">
                        <option></option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4">
                    <label class="control-label" :for="`tickets_${ticket.number}__grade`">Grade</label>
                    <select class="form-control" :id="`tickets_${ticket.number}__grade`" :name="`tickets[${ticket.number}][grade]`" v-model="ticket.grade">
                        <option></option>
                        <option value="6">6th</option>
                        <option value="7">7th</option>
                        <option value="8">8th</option>
                        <option value="9">9th</option>
                        <option value="10">10th</option>
                        <option value="11">11th</option>
                        <option value="12">12th</option>
                    </select>
                    <p class="form-text mb-0" style="line-height:1"><small class="text-muted">Grade completed as of Spring 2017.</small></p>
                </div>
                <div class="form-group col-lg-8">
                    <label class="control-label" :for="`tickets_${ticket.number}__school`">School</label>
                    <input class="form-control" type="text" :id="`tickets_${ticket.number}__school`" :name="`tickets[${ticket.number}][school]`" v-model="ticket.school">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" :for="`tickets_${ticket.number}__allergies`">Allergies</label>
                <textarea class="form-control" :id="`tickets_${ticket.number}__allergies`" :name="`tickets[${ticket.number}][allergies]`" v-model="ticket.allergies" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label class="control-label" :for="`tickets_${ticket.number}__roommate_requested`">Roommate Requested <small class="text-muted">(optional)</small></label>
                <input class="form-control" type="text" :name="`tickets[${ticket.number}][roommate_requested]`" v-model="ticket.roommate_requested" :id="`tickets_${ticket.number}__roommate_requested`">
            </div>
            <div class="form-group">
                <label class="control-label" :for="`tickets_${ticket.number}__travel_plans`">Alternate Arrival/Departure Plans <small class="text-muted">(optional)</small></label>
                <input class="form-control" type="text" :name="`tickets[${ticket.number}][travel_plans]`" v-model="ticket.travel_plans" :id="`tickets_${ticket.number}__travel_plans`">
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['ticket'],
        data() {
            return {

            };
        }
    }
</script>
