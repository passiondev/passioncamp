<template>
    <div class="card mb-3">
        <header class="card-header">
            <h4>Student #{{ index + 1 }}</h4>
        </header>
        <div class="card-block ticket">
            <div class="row">
                <div class="form-group col-lg-6">
                    <label class="control-label" :for="`tickets_${index}__first_name`">First Name</label>
                    <input class="form-control" type="text" :id="`tickets_${index}__first_name`" :name="`tickets[${index}][first_name]`" v-model="ticket.first_name">
                </div>
                <div class="form-group col-lg-6">
                    <label class="control-label" :for="`tickets_${index}__last_name`">Last Name</label>
                    <input class="form-control" type="text" :id="`tickets_${index}__last_name`" :name="`tickets[${index}][last_name]`" v-model="ticket.last_name">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label class="control-label" :for="`tickets_${index}__email`">Email Address</label>
                    <input class="form-control" type="email" :id="`tickets_${index}__email`" :name="`tickets[${index}][email]`" v-model="ticket.email">
                </div>
                <div class="form-group col-lg-6">
                    <label class="control-label" :for="`tickets_${index}__phone`">Phone Number</label>
                    <input class="form-control" type="text" :id="`tickets_${index}__phone`" :name="`tickets[${index}][phone]`" v-model="ticket.phone">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4">
                    <label class="control-label" :for="`tickets_${index}__gender`">Gender</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" value="M" :name="`tickets[${index}][gender]`" v-model="ticket.gender" :id="`tickets_${index}__gender`"> Male
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" value="F" :name="`tickets[${index}][gender]`" v-model="ticket.gender" :id="`tickets_${index}__gender`"> Female
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4">
                    <label class="control-label" :for="`tickets_${index}__grade`">Grade</label>
                    <select class="form-control" :id="`tickets_${index}__grade`" :name="`tickets[${index}][grade]`" v-model="ticket.grade">
                        <option></option>
                        <option value="6" v-if="grades.includes(6)">6th</option>
                        <option value="7" v-if="grades.includes(7)">7th</option>
                        <option value="8" v-if="grades.includes(8)">8th</option>
                        <option value="9" v-if="grades.includes(9)">9th</option>
                        <option value="10" v-if="grades.includes(10)">10th</option>
                        <option value="11" v-if="grades.includes(11)">11th</option>
                        <option value="12" v-if="grades.includes(12)">12th</option>
                    </select>
                    <p class="form-text mb-0" style="line-height:1"><small class="text-muted">Grade completed as of Spring 2019.</small></p>
                </div>
                <div class="form-group col-lg-8">
                    <label class="control-label" :for="`tickets_${index}__school`">School</label>
                    <input class="form-control" type="text" :id="`tickets_${index}__school`" :name="`tickets[${index}][school]`" v-model="ticket.school">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" :for="`tickets_${index}__roommate_requested`">Roommate Requested <small class="text-muted">(optional)</small></label>
                <input class="form-control" type="text" :name="`tickets[${index}][roommate_requested]`" v-model="ticket.roommate_requested" :id="`tickets_${index}__roommate_requested`">
                <p class="form-text mb-0" style="line-height:1"><small class="text-muted">Roommate requests will be considered but are not guaranteed.</small></p>
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
                                <input type="checkbox" :name="`tickets[${index}][considerations][nut]`" value="nut" class="form-check-input" v-model="nut">
                                Peanut/Nut Allergy
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${index}][considerations][vegetarian]`" value="vegetarian" class="form-check-input" v-model="vegetarian">
                                Vegetarian
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${index}][considerations][vegan]`" value="vegan" class="form-check-input" v-model="vegan">
                                Vegan
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${index}][considerations][gluten]`" value="gluten" class="form-check-input" v-model="gluten">
                                Gluten/Celiac
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${index}][considerations][dairy]`" value="dairy" class="form-check-input" v-model="dairy">
                                Dairy Allergy
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" value="true" class="form-check-input" v-model="other_toggle">
                                <input v-if="other_toggle" type="text" :name="`tickets[${index}][considerations][other]`" :id="`considerations_${index}_other`" placeholder="Other..." class="form-control form-control-sm" v-model="other">
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
                                <input v-if="drug_toggle" type="text" :name="`tickets[${index}][considerations][drug]`" :id="`considerations_${index}_drug`" placeholder="Please list name of drug..." class="form-control form-control-sm" v-model="drug">
                                <template v-else>Drug Allergy</template>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label w-100">
                                <input type="checkbox" class="form-check-input" v-model="physical_toggle">
                                <input v-if="physical_toggle" type="text" :name="`tickets[${index}][considerations][physical]`" :id="`considerations_${index}_physical`" placeholder="Please provide more info..." class="form-control form-control-sm" v-model="physical">
                                <template v-else>Physical Impairment</template>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${index}][considerations][visual]`" value="visual" class="form-check-input" v-model="visual">
                                Visual Impairment
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" :name="`tickets[${index}][considerations][hearing]`" value="hearing" class="form-check-input" v-model="hearing">
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
        props: {
            ticket: Object,
            index: Number,
            grades: {
                type: Array,
                default: [6,7,8,9,10,11,12]
            }
        },
        created() {
            if (! this.ticket.considerations) {
                this.ticket.considerations = {};
            }
        },
        data() {
            return {
                food_toggle: Object.keys(_.pick(this.ticket.considerations, ['nut', 'vegetarian', 'gluten', 'dairy', 'other'])).length,
                accessibility_toggle: Object.keys(_.pick(this.ticket.considerations, ['drug', 'physical', 'visual', 'hearing'])).length,
                other_toggle: Object.keys(_.pick(this.ticket.considerations, ['other'])).length,
                drug_toggle: Object.keys(_.pick(this.ticket.considerations, ['drug'])).length,
                physical_toggle: Object.keys(_.pick(this.ticket.considerations, ['physical'])).length,
                nut: this.getConsideration('nut'),
                vegetarian: this.getConsideration('vegetarian'),
                vegan: this.getConsideration('vegan'),
                gluten: this.getConsideration('gluten'),
                dairy: this.getConsideration('dairy'),
                other: this.getConsideration('other'),
                drug: this.getConsideration('drug'),
                physical: this.getConsideration('physical'),
                visual: this.getConsideration('visual'),
                hearing: this.getConsideration('hearing'),
            };
        },
        methods: {
            getConsideration(consideration) {
                return this.ticket.considerations && this.ticket.considerations[consideration];
            }
        },
        watch: {
            other_toggle(toggle) {
                if (toggle) {
                    setTimeout(() => {
                        document.getElementById(`considerations_${this.index}_other`).focus()
                    }, 300)
                }
            },
            drug_toggle(toggle) {
                if (toggle) {
                    setTimeout(() => {
                        document.getElementById(`considerations_${this.index}_drug`).focus()
                    }, 300)
                }
            },
            physical_toggle(toggle) {
                if (toggle) {
                    setTimeout(() => {
                        document.getElementById(`considerations_${this.index}_physical`).focus()
                    }, 300)
                }
            }
        }
    }
</script>
