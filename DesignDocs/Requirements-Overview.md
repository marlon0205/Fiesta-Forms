## Basic Idea
We are creating a web-application that other companies aren't just using, but they want to have their own dedicated server with custom branding to run and administer their forms on.
The web-server would **presumably** be hosted by us, but the company should have the ability to [[#Connecting to Databases of Companies|connect their own database]] to their instance of our product.

## Requirements
- [Source Document]

### Roles
- **Admins:** Users that have the ability to create forms that *Guests* can answer
- **Customers:** Users that can fill out forms created by *Admins*
- **Guests:** Users that can see the results of forms, but they can neither create them, nor fill them out

#### Admins
- Ability to Register new ones
	- Backend only is permitted
- Ability to log in
- **Dedicated** *Admin*-Page
	- Only accessible if you are logged in as *Admin*
	- Should be able to *create*, *edit* and *delete* forms
	- Manage forms *Questions* and *Answers*

#### Customers
- Ability to register and log in
	- Data is being validated by the *Customer company*
	- Being able to register a *Customer* to the company-database suffices **(???)**
- Ability to change profile-information after registration
	- Including password-reset

### GUI
- Display poll-results graphically
- Search forms by name
- Filter forms by product-/service-category
- Display most recent forms (by creation date) on the homepage
- The following lists need to be available
	- All active forms
	- Archive of all expired forms
	- Statistics **(?)**
- Page to edit ones profile as *Customer*
- A list-view of all available forms including results 
	- (All non-Archived or all active forms **???**)

### Features
#### Forms
A form is a collection of *Questions* with *Answer*-Fields. They also contain a *Title*, *Description*, a *End-Date* and a associated product-/service-category.

Once a Form has surpassed its *End Date*, *Answers* can no longer be submitted and the form should be marked as *Inactive*.

*Admins* should be able to archive *Inactive* forms. **(Whatever that means???)**

Active Forms can be filled out only by *Customers*. Every *Customer* can only answer once per form.

Answer-Fields should be validated **before** being sent to the server

#### Rewards-Program
Create a, incentive for *Customers* to answer forms by handing out **Rewards** for the most active *Customers*. These rewards and conditions should be chosen by the *Customer*.

#### Connecting to Databases of Companies
- The *Customer company* already has a database of product-/service-categories
	- These can be sent as JSON to our service
- The *Customer company* already has a database of its *customers*, and wants to be able to cross-reference out *customer*-data with their own
	- We need to be able to sent *customer*-data via JSON

- All relevant data includes:
	- Employe-data
	- *Customer*-data
	- Product-data
	- Service-data (as in services the *Customer-company* sells)
