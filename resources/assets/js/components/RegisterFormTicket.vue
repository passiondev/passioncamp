<template>
    <div class="card mb-3">
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
                <label class="control-label" :for="`tickets_${ticket.number}__roommate_requested`">Roommate Requested <small class="text-muted">(optional)</small></label>
                <input class="form-control" type="text" :name="`tickets[${ticket.number}][roommate_requested]`" v-model="ticket.roommate_requested" :id="`tickets_${ticket.number}__roommate_requested`">
            </div>
            <div class="form-group">
                <label class="control-label" :for="`tickets_${ticket.number}__travel_plans`">Alternate Arrival/Departure Plans <small class="text-muted">(optional)</small></label>
                <input class="form-control" type="text" :name="`tickets[${ticket.number}][travel_plans]`" v-model="ticket.travel_plans" :id="`tickets_${ticket.number}__travel_plans`">
            </div>
            <div class="form-group">
                <fieldset class="form-group">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" v-model="food_toggle">
                            Food Allergies
                        </label>
                    </div>
                    <div class="pl-4 mb-2" v-if="food_toggle">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${ticket.number}][considerations][nut]`" value="nut" class="form-check-input" v-model="nut">
                                Peanut/Nut Allergy
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${ticket.number}][considerations][vegetarian]`" value="vegetarian" class="form-check-input" v-model="vegetarian">
                                Vegetarian/Vegan
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${ticket.number}][considerations][gluten]`" value="gluten" class="form-check-input" v-model="gluten">
                                Gluten/Celiac
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${ticket.number}][considerations][dairy]`" value="dairy" class="form-check-input" v-model="dairy">
                                Dairy Allergy
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" value="true" class="form-check-input" v-model="other_toggle">
                                <input v-if="other_toggle" type="text" :name="`tickets[${ticket.number}][considerations][other]`" :id="`considerations_${ticket.number}_other`" placeholder="Other..." class="form-control form-control-sm" v-model="other">
                                <template v-else>Other</template>
                            </label>
                        </div>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" v-model="accessibility_toggle">
                            Medical/Accessibility
                        </label>
                    </div>
                    <div class="pl-4 mb-2" v-if="accessibility_toggle">
                        <div class="form-check">
                            <label class="form-check-label w-100">
                                <input type="checkbox" class="form-check-input" v-model="drug_toggle">
                                <input v-if="drug_toggle" type="text" :name="`tickets[${ticket.number}][considerations][drug]`" :id="`considerations_${ticket.number}_drug`" placeholder="Please list name of drug..." class="form-control form-control-sm" v-model="drug">
                                <template v-else>Drug Allergy</template>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label w-100">
                                <input type="checkbox" class="form-check-input" v-model="physical_toggle">
                                <input v-if="physical_toggle" type="text" :name="`tickets[${ticket.number}][considerations][physical]`" :id="`considerations_${ticket.number}_physical`" placeholder="Please provide more info..." class="form-control form-control-sm" v-model="physical">
                                <template v-else>Physical Impairment</template>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${ticket.number}][considerations][visual]`" value="visual" class="form-check-input" v-model="visual">
                                Visual Impairment
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${ticket.number}][considerations][hearing]`" value="hearing" class="form-check-input" v-model="hearing">
                                Hearing Impairment
                            </label>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['ticket'],
        data() {
            return {
                food_toggle: Object.keys(_.pick(this.ticket.considerations, ['nut', 'vegetarian', 'gluten', 'dairy', 'other'])).length,
                accessibility_toggle: Object.keys(_.pick(this.ticket.considerations, ['drug', 'physical', 'visual', 'hearing'])).length,
                other_toggle: Object.keys(_.pick(this.ticket.considerations, ['other'])).length,
                drug_toggle: Object.keys(_.pick(this.ticket.considerations, ['drug'])).length,
                physical_toggle: Object.keys(_.pick(this.ticket.considerations, ['physical'])).length,
                nut: this.ticket.considerations.nut,
                vegetarian: this.ticket.considerations.vegetarian,
                gluten: this.ticket.considerations.gluten,
                dairy: this.ticket.considerations.dairy,
                other: this.ticket.considerations.other,
                drug: this.ticket.considerations.drug,
                physical: this.ticket.considerations.physical,
                visual: this.ticket.considerations.visual,
                hearing: this.ticket.considerations.hearing,
            };
        },
        watch: {
            other_toggle(toggle) {
                if (toggle) {
                    setTimeout(() => {
                        document.getElementById(`considerations_${this.ticket.number}_other`).focus()
                    }, 300)
                }
            },
            drug_toggle(toggle) {
                if (toggle) {
                    setTimeout(() => {
                        document.getElementById(`considerations_${this.ticket.number}_drug`).focus()
                    }, 300)
                }
            },
            physical_toggle(toggle) {
                if (toggle) {
                    setTimeout(() => {
                        document.getElementById(`considerations_${this.ticket.number}_physical`).focus()
                    }, 300)
                }
            }
        }
    }
</script>
