<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone_number
 * @property string|null $username
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property int $approved
 * @property int|null $approved_by
 * @property string|null $approved_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\UserProfile|null $profile
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property \Illuminate\Support\Carbon $date_of_birth
 * @property string $gender
 * @property string $address
 * @property string $phone_number
 * @property string $bio
 * @property string $profile_picture
 * @property string $marital_status
 * @property string $education
 * @property string $occupation
 * @property numeric $annual_income
 * @property string $religion
 * @property string $caste
 * @property string $mother_tongue
 * @property string $state
 * @property string $city
 * @property int $height_cm
 * @property numeric $weight_kg
 * @property string $dietary_preferences
 * @property string $smoking_habits
 * @property string $drinking_habits
 * @property string $hobbies_interests
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserProfileFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereAnnualIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereCaste($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereDietaryPreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereDrinkingHabits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereHeightCm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereHobbiesInterests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereMotherTongue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereSmokingHabits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereWeightKg($value)
 */
	class UserProfile extends \Eloquent {}
}

