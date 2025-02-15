#!/usr/bin/php
# Dataset Version - the DataId
<?php
error_reporting( E_ALL | E_STRICT );
require_once("function.php");
init();

?>
<?php
$section="dataid" ;
$sectionExampleURI="https://databus.dbpedia.org/janni/onto_dep_projectx/dbpedia-ontology/2021-12-06#Dataset";
$owl=
'dataid:Dataset
	a owl:Class ;
	rdfs:label "Databus Dataset"@en ;
	rdfs:comment "A collection of data, available for access in one or more formats. Dataset resources describe the concept of the dataset, not its manifestation (the data itself), which can be acquired as a Distribution"@en ; 
	rdfs:subClassOf void:Dataset, dcat:Dataset, prov:Entity ; #copied from dataid ontology
	rdfs:isDefinedBy <http://dataid.dbpedia.org/ns/core#> . 
';

$shacl='<#dataset-exists>
	a sh:NodeShape ;
	sh:targetNode dataid:Dataset ;
	sh:property [
	  sh:path [ sh:inversePath rdf:type ] ;
	  sh:minCount 1 ;
	  sh:maxCount 1 ;
	  sh:message "Exactly one subject with an rdf:type of dataid:Dataset must occur."@en ;
	] ;
	sh:property [
		sh:path [ sh:inversePath rdf:type ] ;
		  sh:nodekind sh:IRI ;
		sh:pattern "/[a-zA-Z0-9]{4,}/[a-zA-Z0-9\\\\-_\\\\.]{3,}/[a-zA-Z0-9\\\\-_\\\\.]{3,}/[a-zA-Z0-9\\\\-_\\\\.]{3,}#Dataset$" ;
		sh:message "IRI for dataid:Dataset must match /USER/GROUP/ARTIFACT/VERSION#Dataset , |USER|>3"@en ;
  ] . ';


$example='"@type": "dataid:Dataset",';

$context='"Dataset": 	"dataid:Dataset" ';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>


## title
<?php
$owl='dct:title
	rdfs:label "Title"@en ;
	rdfs:comment "A name given to the resource."@en ;
	rdfs:isDefinedBy <http://purl.org/dc/terms/> ;
	rdfs:range rdfs:Literal ;
	rdfs:subPropertyOf <http://purl.org/dc/elements/1.1/title> .';

$shacl='<#title-dataid>
	a sh:NodeShape ;
	sh:targetClass dataid:Dataset ;
	sh:property [
		sh:path dct:title ;
		sh:severity sh:Violation ;
		sh:message "Required property dct:title MUST occur exactly once without language tag."@en ;
        sh:qualifiedValueShape [ sh:datatype xsd:string ] ;
		sh:qualifiedMinCount 1 ;
		sh:qualifiedMaxCount 1 ;		
    ] ;
        sh:property [
		sh:path dct:title ;
		sh:severity sh:Violation ;
		sh:message "Besides the required occurance of dct:title without language tag, dct:title can be used with language tag, but each language only once."@en ;
		sh:uniqueLang true ;
	] . ';

$example='"title": "DBpedia Ontology",';

$context='duplicate';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>


## abstract
<?php
$owl='dct:abstract
	rdfs:label "Abstract"@en ;
	rdfs:comment "A summary of the resource."@en ;
	rdfs:isDefinedBy <http://purl.org/dc/terms/> ;
	rdfs:subPropertyOf <http://purl.org/dc/elements/1.1/description>, dct:description .';

$shacl='<#abstract-dataid>
	a sh:NodeShape ;
    sh:targetClass dataid:Dataset ;
    sh:property [
	    sh:path dct:abstract ;
	    sh:severity sh:Violation ;
	    sh:message "Required property dct:abstract MUST occur at least once without language tag."@en ;
	    sh:qualifiedValueShape [ sh:datatype xsd:string ] ;
		sh:qualifiedMinCount 1 ;
		sh:qualifiedMaxCount 1 ;	
	];
	sh:property [
		sh:path dct:abstract ;
	    sh:severity sh:Violation ;
	    sh:message "Besides the required occurance of dct:abstract without language tag, each occurance of dct:abstract must have less than 200 characters and each language must occure only once. "@en ;
	    sh:uniqueLang true;
	    sh:maxLength 200 ;
	] . ';

$example='"abstract": "Registered a version of the DBpedia Ontology into my account",';

$context='duplicate';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>


## description
<?php
$owl='dct:description
	dct:description "Description may include but is not limited to: an abstract, a table of contents, a graphical representation, or a free-text account of the resource."@en ;
	rdfs:comment "An account of the resource."@en ;
	rdfs:isDefinedBy <http://purl.org/dc/terms/> ;
	rdfs:label "Description"@en ;
	rdfs:subPropertyOf <http://purl.org/dc/elements/1.1/description> .';

$shacl='<#description-dataid>
	a sh:NodeShape ;
    sh:targetClass dataid:Dataset ;
    sh:property [
		sh:path dct:description ;
		sh:severity sh:Violation ;
		sh:message "Required property dct:description MUST occur exactly once without language tag."@en ;
        sh:qualifiedValueShape [ sh:datatype xsd:string ] ;
		sh:qualifiedMinCount 1 ;
		sh:qualifiedMaxCount 1 ;		
    ] ;
        sh:property [
		sh:path dct:description ;
		sh:severity sh:Violation ;
		sh:message "Besides the required occurance of dct:description without language tag, dct:title can be used with language tag, but each language only once."@en ;
		sh:uniqueLang true ;
	] . ';

$example='"description": "Registered a version of the DBpedia Ontology into my account. Using markdown:\n  1. This is the version used in [project x](http://example.org) as a stable snapshot dependency\n  2. License was checked -> CC-BY\n",';

$context='duplicate';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>


## publisher

<?php
$owl='dct:publisher
	dcam:rangeIncludes dct:Agent ;
	rdfs:comment "An entity responsible for making the resource available."@en ;
	rdfs:isDefinedBy <http://purl.org/dc/terms/> ;
	rdfs:label "Publisher"@en ;
	rdfs:subPropertyOf <http://purl.org/dc/elements/1.1/publisher> .';

$shacl='<#has-publisher>
	a sh:PropertyShape ;
  sh:targetClass dataid:Dataset ;
	sh:severity sh:Violation ;
	sh:message "Required property dct:publisher MUST occur exactly once and have URI/IRI as value"@en ;
	sh:path dct:publisher;
	sh:minCount 1 ;
	sh:maxCount 1 ;
	sh:nodeKind sh:IRI .';

$example='"publisher": TODO';

$context='"publisher": {
      "@id": "dct:publisher",
      "@type": "@id"
    }';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>


## group

<?php #TODO single value restriction??
$owl=
'dataid:group a owl:ObjectProperty; 
	rdfs:label "has parent Group"@en ;
	rdfs:comment "Defines the Databus Group a Databus Artifact belongs to"@en ;
	rdfs:domain dataid:Artifact ;
	rdfs:range dataid:Group ;
	rdfs:subPropertyOf dct:isPartOf ; 
	rdfs:isDefinedBy <http://dataid.dbpedia.org/ns/core#> . 
';

$shacl='<#has-group>
	a sh:PropertyShape ;
	sh:targetClass dataid:Dataset ;
	sh:severity sh:Violation ;
	sh:message "Required property dataid:group MUST occur exactly once AND be of type IRI AND must match /USER/GROUP , |USER|>3"@en ;
	sh:path dataid:group ;
	sh:minCount 1 ;
	sh:maxCount 1 ;
	sh:nodeKind sh:IRI ;
  sh:pattern "/[a-zA-Z0-9]{4,}/[a-zA-Z0-9\\\\-_\\\\.]{3,}$" .

<#is-group-uri-correct>
	a sh:NodeShape;
	sh:targetClass dataid:Dataset ;
	sh:sparql [
		sh:message "Dataset URI must contain the group URI of the associated group." ;
		sh:prefixes dataid: ;
    sh:select """
			SELECT $this ?group
			WHERE {
				$this <http://dataid.dbpedia.org/ns/core#group> ?group .
        FILTER(!strstarts(str($this), str(?group)))
			}
			""" ;
	] .';

$example='"group": "https://databus.dbpedia.org/janni/onto_dep_projectx",';

$context='duplicate';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>


## artifact 


<?php
autonote();


$owl=
'dataid:artifact a owl:ObjectProperty; 
	rdfs:label "has parent Artifact"@en ;
	rdfs:comment "Defines the Databus Artifact a Databus Dataset belongs to"@en ;
	rdfs:domain dataid:Dataset ;
	rdfs:range dataid:Artifact ;
	rdfs:subPropertyOf dct:isPartOf ; 
	rdfs:isDefinedBy <http://dataid.dbpedia.org/ns/core#> . ';

$shacl='<#has-artifact>
	a sh:PropertyShape ;
	sh:targetClass dataid:Dataset ;
	sh:severity sh:Violation ;
	sh:message "Required property dataid:artifact MUST occur exactly once AND be of type IRI AND must match /USER/GROUP/ARTIFACT , |USER|>3"@en ;
	sh:path dataid:artifact ;
	sh:minCount 1 ;
	sh:maxCount 1 ;
	sh:nodeKind sh:IRI  ;
  sh:pattern "/[a-zA-Z0-9]{4,}/[a-zA-Z0-9\\\\-_\\\\.]{3,}/[a-zA-Z0-9\\\\-_\\\\.]{3,}$" .

<#is-artifact-uri-correct>
	a sh:NodeShape;
	sh:targetClass dataid:Dataset ;
	sh:sparql [
		sh:message "Dataset URI must contain the artifact URI of the associated artifact." ;
		sh:prefixes dataid: ;
    sh:select """
			SELECT $this ?artifact
			WHERE {
				$this <http://dataid.dbpedia.org/ns/core#artifact> ?artifact .
        FILTER(!strstarts(str($this), str(?artifact)))
			}
			""" ;
	] .';

$example='"artifact": "https://databus.dbpedia.org/janni/onto_dep_projectx/dbpedia-ontology",';

$context='"artifact": {
      "@id": "dataid:artifact",
      "@type": "@id"
    }';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>


## version

<?php
$owl='missing maybe obsolete';

$shacl='<#has-version>
	a sh:PropertyShape ;
	sh:targetClass dataid:Dataset ;
	sh:severity sh:Violation ;
	sh:message "Required property dataid:version MUST occur exactly once AND be of type IRI /USER/GROUP/ARTIFACT/VERSION , |USER|>3"@en ;
	sh:path dataid:version ;
	sh:minCount 1 ;
	sh:maxCount 1 ;
	sh:nodeKind sh:IRI  ;
  sh:pattern "/[a-zA-Z0-9]{4,}/[a-zA-Z0-9\\\\-_\\\\.]{3,}/[a-zA-Z0-9\\\\-_\\\\.]{3,}/[a-zA-Z0-9\\\\-_\\\\.]{3,}$" .

<#is-version-uri-correct>
	a sh:NodeShape;
	sh:targetClass dataid:Dataset ;
	sh:sparql [
		sh:message "Dataset URI must contain the version URI of the associated version." ;
		sh:prefixes dataid: ;
    sh:select """
			SELECT $this ?version
			WHERE {
				$this <http://dataid.dbpedia.org/ns/core#version> ?version .
        FILTER(!strstarts(str($this), str(?version)))
			}
			""" ;
	] .';

$example='"version": "https://databus.dbpedia.org/janni/onto_dep_projectx/dbpedia-ontology/2021-12-06",';

$context='"version": {
      "@id": "dataid:version",
      "@type": "@id"
    }';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>


## hasVersion

Note: see section versioning above

<?php
$owl='dct:hasVersion
	dct:description "Changes in version imply substantive changes in content rather than differences in format. This property is intended to be used with non-literal values. This property is an inverse property of Is Version Of."@en ;
	rdfs:comment "A related resource that is a version, edition, or adaptation of the described resource."@en ;
	rdfs:isDefinedBy <http://purl.org/dc/terms/> ;
	rdfs:label "Has Version"@en ;
	rdfs:subPropertyOf <http://purl.org/dc/elements/1.1/relation>, dct:relation .';

$shacl='<#has-hasVersion-dataset>
	a sh:PropertyShape ;
	sh:targetClass dataid:Dataset ;
	sh:severity sh:Violation ;
	sh:message "Required property dct:hasVersion MUST occur exactly once AND be of type Literal"@en ;
	sh:path dct:hasVersion ;
	sh:minCount 1 ;
	sh:maxCount 1 ;
	sh:nodeKind sh:Literal .';

$example='"hasVersion": "2021-12-06",';

$context='"hasVersion": 	{"@id": "dct:hasVersion"}';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>


## issued

<?php
$owl='dct:issued
	rdfs:label "Date Issued"@en ;
	rdfs:comment "Date of formal issuance of the resource."@en ;
	dct:description "Recommended practice is to describe the date, date/time, or period of time as recommended for the property Date, of which this is a subproperty."@en ;
	dct:issued "2000-07-11"^^<http://www.w3.org/2001/XMLSchema#date> ;
	rdfs:isDefinedBy <http://purl.org/dc/terms/> ;
	rdfs:range rdfs:Literal ;
	rdfs:subPropertyOf <http://purl.org/dc/elements/1.1/date>, dct:date .';

$shacl='<#has-issued>
	a sh:PropertyShape ;
	sh:targetClass dataid:Dataset ;
	sh:severity sh:Violation ;
	sh:message "Required property dct:issued MUST occur exactly once AND have xsd:dateTime as value"@en ;
	sh:path dct:issued;
	sh:minCount 1 ;
	sh:maxCount 1 ;
	sh:datatype xsd:dateTime .';

$example='"issued": "2021-12-06T11:34:17Z",';

$context='"issued": {
      "@id": "dct:issued",
      "@type": "xsd:dateTime"
    }';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>

## modified

Note: dct:modified is *always* set by the Databus on post.

<?php
$owl='dct:modified
	rdfs:label "Date Modified"@en ;
	rdfs:comment "Date on which the resource was changed."@en ;
	dct:description "Recommended practice is to describe the date, date/time, or period of time as recommended for the property Date, of which this is a subproperty."@en ;
	rdfs:isDefinedBy <http://purl.org/dc/terms/> ;
	rdfs:range rdfs:Literal ;
	rdfs:subPropertyOf <http://purl.org/dc/elements/1.1/date>, dcterms:date .';

$shacl='<#has-modified>
	a sh:PropertyShape ;
	sh:targetClass dataid:Dataset ;
	sh:severity sh:Violation ;
	sh:message "Required property dct:modified MUST occur exactly once AND have xsd:dateTime as value"@en ;
	sh:path dct:modified;
	sh:minCount 1 ;
	sh:maxCount 1 ;
	sh:datatype xsd:dateTime .';

$example='"modified": "%NOW%",';

$context='"modified": {
      "@id": "dct:modified",
      "@type": "xsd:dateTime"
    }';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>


## license

Note:
* see roadmap above for planned changes
* must be an IRI
* license is set at the dataid:Dataset node, but is always valid for all distributions, which is also reflected by signing the tractacte.
* context.jsonld contains `"@context":{"@base": null },` to prevent creating local IRIs.

<?php
$owl='dct:license
	rdfs:label "License"@en ;
	rdfs:comment "A legal document giving official permission to do something with the resource."@en ;
	dct:description "Recommended practice is to identify the license document with a URI. If this is not possible or feasible, a literal value that identifies the license may be provided."@en ;
	dcam:rangeIncludes dct:LicenseDocument ;
	rdfs:isDefinedBy <http://purl.org/dc/terms/> ;
	rdfs:subPropertyOf <http://purl.org/dc/elements/1.1/rights>, dct:rights .';

$shacl='<#has-license>
	a sh:PropertyShape ;
	sh:targetClass dataid:Dataset ;
	sh:severity sh:Violation ;
	sh:message "Required property dct:license MUST occur exactly once and have URI/IRI as value"@en ;
	sh:path dct:license;
	sh:minCount 1 ;
	sh:maxCount 1 ;
	sh:nodeKind sh:IRI .';

$example='"license": "http://creativecommons.org/licenses/by/4.0/",';

$context='"license": {
      "@context":{"@base": null },
      "@id": "dct:license",
      "@type": "@id"
    }';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>

## distribution
<?php
$owl='dcat:distribution
  a owl:ObjectProperty ;
  rdfs:label "distribution"@en ;
  rdfs:comment "An available distribution of the dataset."@en ;
  rdfs:isDefinedBy <http://www.w3.org/TR/vocab-dcat/> ;
  rdfs:domain dcat:Dataset ;
  rdfs:range dcat:Distribution ;
  rdfs:subPropertyOf dct:relation ;
  skos:definition "An available distribution of the dataset."@en .';

$shacl='<#has-distribution>
	a sh:PropertyShape ;
	sh:targetClass dataid:Dataset ;
	sh:severity sh:Violation ;
	sh:message "Required property dcat:distribution MUST occur at least once AND have URI/IRI as value"@en ;
	sh:path dcat:distribution;
	sh:minCount 1 ;
	sh:nodeKind sh:IRI .';

$example='"distribution": [{
          		"@id": "https://databus.dbpedia.org/janni/onto_dep_projectx/dbpedia-ontology/2021-12-06#ontology--DEV_type=parsed_sorted.nt",
          		"@type": "dataid:Part",
          		"file": "https://databus.dbpedia.org/janni/onto_dep_projectx/dbpedia-ontology/2021-12-06/ontology--DEV_type=parsed_sorted.nt",
          		"format": "nt",
          		"compression": "none",
          		"downloadURL": "https://akswnc7.informatik.uni-leipzig.de/dstreitmatter/archivo/dbpedia.org/ontology--DEV/2021.07.09-070001/ontology--DEV_type=parsed_sorted.nt",
          		"byteSize": "4439722",
          		"sha256sum": "b3aa40e4a832e69ebb97680421fbeff968305931dafdb069a8317ac120af0380",
          		"hasVersion": "2021-12-06",
          		"dcv:type": "parsed_sorted"
              }]';

$context='"distribution": {
      "@type": "@id",
      "@id": "dcat:distribution"
}';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>

## proof

<?php
$owl=
'sec:proof a owl:ObjectProperty; 
	rdfs:label "has cryptographic proof"@en ;
	rdfs:comment "The proof property is used to associate a proof with a graph of information. The proof property is typically not included in the canonicalized graph that is then digested, and digitally signed."@en ;
	#rdfs:domain  ;
	#rdfs:range  ;
	rdfs:isDefinedBy <https://w3id.org/security#> . 
';


$shacl='';

$example='"proof": {
  "@type": "dataid:DatabusTractateV1",
  "signature": "d61a05ca4810367f361f17500304a168aab27a3119c93a18c00bce1775dfd6b1"
}';

$context='"signature":	{"@id": "sec:signature"},
"proof":	{"@id": "sec:proof"}';

table($section,$sectionExampleURI,$owl,$shacl,$example,$context);
?>